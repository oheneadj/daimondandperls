<?php

declare(strict_types=1);

use App\Enums\BookingStatus;
use App\Enums\SettingType;
use App\Livewire\Admin\Bookings\Show as AdminBookingShow;
use App\Livewire\Admin\Reviews\ReviewIndex;
use App\Livewire\Admin\Settings\AdminSettings;
use App\Livewire\Review\ReviewForm;
use App\Livewire\Review\ReviewShare;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\LoyaltyTransaction;
use App\Models\Review;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\FriendReferralNotification;
use App\Notifications\ReviewRequestNotification;
use App\Services\LoyaltyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function reviewAdminUser(): User
{
    $role = Role::updateOrCreate(['slug' => 'super_admin'], ['name' => 'Super Admin', 'description' => 'Super Administrator']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function reviewCustomer(int $points = 0): Customer
{
    $user = User::factory()->create();

    return Customer::factory()->create(['user_id' => $user->id, 'loyalty_points' => $points]);
}

function enableReviewSettings(int $reward = 25): void
{
    Cache::forget('app_settings');
    Setting::updateOrCreate(['key' => 'review_enabled'], ['value' => '1', 'type' => SettingType::Boolean, 'group' => 'reviews', 'label' => 'Review System Enabled']);
    Setting::updateOrCreate(['key' => 'review_points_reward'], ['value' => (string) $reward, 'type' => SettingType::Integer, 'group' => 'reviews', 'label' => 'Points Awarded per Review']);
    Setting::updateOrCreate(['key' => 'loyalty_enabled'], ['value' => '1', 'type' => SettingType::Boolean, 'group' => 'loyalty', 'label' => 'Loyalty Enabled']);
    Cache::forget('app_settings');
}

// ── completeBooking creates review + dispatches SMS ───────────────────────────

it('sends review request SMS when booking is completed', function () {
    Notification::fake();
    enableReviewSettings();

    $customer = reviewCustomer();
    $booking = Booking::factory()->create([
        'customer_id' => $customer->id,
        'status' => BookingStatus::ReadyForDelivery,
    ]);

    $admin = reviewAdminUser();
    $customer->user->update(['phone' => '0201234567']);

    Livewire::actingAs($admin)
        ->test(AdminBookingShow::class, ['booking' => $booking])
        ->call('completeBooking');

    Notification::assertSentTo($customer, ReviewRequestNotification::class);
    $this->assertDatabaseHas('reviews', ['booking_id' => $booking->id]);
});

it('does not send review SMS when review system is disabled', function () {
    Notification::fake();
    Cache::forget('app_settings');
    Setting::updateOrCreate(['key' => 'review_enabled'], ['value' => '0', 'type' => SettingType::Boolean, 'group' => 'reviews', 'label' => 'Review System Enabled']);
    Cache::forget('app_settings');

    $customer = reviewCustomer();
    $booking = Booking::factory()->create([
        'customer_id' => $customer->id,
        'status' => BookingStatus::ReadyForDelivery,
    ]);

    $admin = reviewAdminUser();

    Livewire::actingAs($admin)
        ->test(AdminBookingShow::class, ['booking' => $booking])
        ->call('completeBooking');

    Notification::assertNotSentTo($customer, ReviewRequestNotification::class);
    $this->assertDatabaseMissing('reviews', ['booking_id' => $booking->id]);
});

// ── Token-based access ────────────────────────────────────────────────────────

it('loads the review form for a valid token', function () {
    $customer = reviewCustomer();
    $booking = Booking::factory()->create(['customer_id' => $customer->id]);
    $review = Review::factory()->create(['booking_id' => $booking->id, 'customer_id' => $customer->id]);

    Livewire::test(ReviewForm::class, ['token' => $review->token])
        ->assertOk();
});

it('returns 404 for an invalid token', function () {
    $this->get('/review/invalid-token-xyz')->assertNotFound();
});

// ── Submit review ─────────────────────────────────────────────────────────────

it('customer can submit a star rating', function () {
    enableReviewSettings(25);

    $customer = reviewCustomer();
    $booking = Booking::factory()->create(['customer_id' => $customer->id]);
    $review = Review::factory()->create(['booking_id' => $booking->id, 'customer_id' => $customer->id]);

    Livewire::test(ReviewForm::class, ['token' => $review->token])
        ->set('stars', 5)
        ->set('authorName', 'Alice Johnson')
        ->set('message', 'Great food!')
        ->call('submit')
        ->assertSet('submitted', true);

    $this->assertDatabaseHas('reviews', [
        'id' => $review->id,
        'stars' => 5,
        'author_name' => 'Alice Johnson',
        'is_approved' => true,
    ]);
    $this->assertNotNull($review->fresh()->submitted_at);
});

it('awards loyalty points on review submission', function () {
    enableReviewSettings(25);

    $customer = reviewCustomer();
    $booking = Booking::factory()->create(['customer_id' => $customer->id]);
    $review = Review::factory()->create(['booking_id' => $booking->id, 'customer_id' => $customer->id]);

    Livewire::test(ReviewForm::class, ['token' => $review->token])
        ->set('stars', 4)
        ->set('authorName', 'Bob')
        ->call('submit');

    expect($customer->fresh()->loyalty_points)->toBe(25);
    expect(LoyaltyTransaction::where('customer_id', $customer->id)->where('type', 'review_bonus')->count())->toBe(1);
});

it('cannot submit a review twice', function () {
    $customer = reviewCustomer();
    $booking = Booking::factory()->create(['customer_id' => $customer->id]);
    $review = Review::factory()->submitted()->create(['booking_id' => $booking->id, 'customer_id' => $customer->id]);

    Livewire::test(ReviewForm::class, ['token' => $review->token])
        ->assertSet('submitted', true)
        ->call('submit'); // no-op

    // Only one transaction should exist (none, as it was factory-created without going through submit)
    expect(LoyaltyTransaction::where('customer_id', $customer->id)->where('type', 'review_bonus')->count())->toBe(0);
});

// ── Friend nomination ─────────────────────────────────────────────────────────

it('can nominate a friend after submitting a review', function () {
    Notification::fake();
    enableReviewSettings();

    $customer = reviewCustomer();
    $booking = Booking::factory()->create(['customer_id' => $customer->id]);
    $review = Review::factory()->submitted()->create([
        'booking_id' => $booking->id,
        'customer_id' => $customer->id,
        'author_name' => 'Alice',
        'reviewer_phone' => '0201234567',
    ]);

    Livewire::test(ReviewForm::class, ['token' => $review->token])
        ->set('friendName', 'John Mensah')
        ->set('friendPhone', '0249876543')
        ->call('nominateFriend')
        ->assertSet('friendNominated', true);

    $this->assertDatabaseHas('reviews', [
        'id' => $review->id,
        'friend_name' => 'John Mensah',
        'friend_phone' => '0249876543',
    ]);
    $this->assertNotNull($review->fresh()->friend_sms_sent_at);
    Notification::assertSentOnDemand(FriendReferralNotification::class);
});

it('cannot nominate a friend more than once per review', function () {
    $customer = reviewCustomer();
    $booking = Booking::factory()->create(['customer_id' => $customer->id]);
    $review = Review::factory()->submitted()->create([
        'booking_id' => $booking->id,
        'customer_id' => $customer->id,
        'friend_name' => 'Already nominated',
        'friend_phone' => '0249876543',
        'friend_sms_sent_at' => now(),
    ]);

    Livewire::test(ReviewForm::class, ['token' => $review->token])
        ->set('friendName', 'Another person')
        ->set('friendPhone', '0201111111')
        ->call('nominateFriend')
        ->assertSet('friendError', 'You have already nominated a friend for this review.');
});

it('cannot nominate a phone number already referred by someone else', function () {
    $customer = reviewCustomer();
    $booking = Booking::factory()->create(['customer_id' => $customer->id]);

    // First review already nominated this phone
    $other = Review::factory()->submitted()->create([
        'booking_id' => $booking->id,
        'friend_phone' => '0249876543',
        'friend_sms_sent_at' => now(),
    ]);

    $review = Review::factory()->submitted()->create([
        'booking_id' => $booking->id,
        'customer_id' => $customer->id,
    ]);

    Livewire::test(ReviewForm::class, ['token' => $review->token])
        ->set('friendName', 'John')
        ->set('friendPhone', '0249876543')
        ->call('nominateFriend')
        ->assertSet('friendError', 'This number has already been referred to us by another customer.');
});

// ── Share page ────────────────────────────────────────────────────────────────

it('friend can view the share page with review', function () {
    $customer = reviewCustomer();
    $booking = Booking::factory()->create(['customer_id' => $customer->id]);
    $review = Review::factory()->submitted()->create([
        'booking_id' => $booking->id,
        'customer_id' => $customer->id,
    ]);

    Livewire::test(ReviewShare::class, ['token' => $review->token])
        ->assertOk();
});

it('share page returns 404 for unsubmitted review', function () {
    $customer = reviewCustomer();
    $booking = Booking::factory()->create(['customer_id' => $customer->id]);
    $review = Review::factory()->create(['booking_id' => $booking->id]);

    $this->get('/reviews/'.$review->token.'/share')->assertNotFound();
});

// ── Admin ─────────────────────────────────────────────────────────────────────

it('admin can view the reviews index', function () {
    $admin = reviewAdminUser();

    Livewire::actingAs($admin)
        ->test(ReviewIndex::class)
        ->assertOk();
});

it('admin can toggle review approval', function () {
    $admin = reviewAdminUser();
    $customer = reviewCustomer();
    $booking = Booking::factory()->create(['customer_id' => $customer->id]);
    $review = Review::factory()->submitted()->create(['booking_id' => $booking->id, 'is_approved' => false]);

    Livewire::actingAs($admin)
        ->test(ReviewIndex::class)
        ->call('approve', $review->id);

    expect($review->fresh()->is_approved)->toBeTrue();
});

// ── Settings ──────────────────────────────────────────────────────────────────

it('admin can save review settings', function () {
    $admin = reviewAdminUser();
    Cache::forget('app_settings');

    Livewire::actingAs($admin)
        ->test(AdminSettings::class)
        ->set('review_enabled', true)
        ->set('review_points_reward', 50)
        ->call('saveReviewSettings')
        ->assertHasNoErrors();

    expect((int) Setting::where('key', 'review_points_reward')->value('value'))->toBe(50);
});

// ── LoyaltyService::creditForReview ───────────────────────────────────────────

it('creditForReview awards points and creates transaction', function () {
    enableReviewSettings(30);

    $customer = reviewCustomer();
    $booking = Booking::factory()->create(['customer_id' => $customer->id]);
    $review = Review::factory()->submitted()->create([
        'booking_id' => $booking->id,
        'customer_id' => $customer->id,
    ]);

    app(LoyaltyService::class)->creditForReview($review);

    expect($customer->fresh()->loyalty_points)->toBe(30);
    expect($review->fresh()->points_awarded)->toBe(30);
    expect(LoyaltyTransaction::where('type', 'review_bonus')->exists())->toBeTrue();
});
