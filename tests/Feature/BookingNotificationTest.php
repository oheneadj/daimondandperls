<?php

use App\Enums\UserRole;
use App\Livewire\Booking\BookingWizard;
use App\Models\Booking;
use App\Models\Package;
use App\Models\User;
use App\Notifications\BookingReceivedNotification;
use App\Services\CartService;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'role' => UserRole::Admin,
        'notification_preference' => \App\Enums\NotificationPreference::Email,
    ]);
});

test('it sends notification to admin when booking is created', function () {
    Notification::fake();

    $package = Package::factory()->create(['price' => 100]);

    // Put item in cart
    $cart = app(CartService::class);
    $cart->add($package->id);

    Livewire::test(BookingWizard::class)
        ->call('nextStep') // 1 (Review) → 2 (Contact)
        ->set('name', 'John Doe')
        ->set('phone', '0240000000')
        ->set('email', 'john@example.com')
        ->call('nextStep') // 2 → 3 (Summary)
        ->call('confirmBooking');

    Notification::assertSentTo(
        [$this->admin],
        BookingReceivedNotification::class
    );
});

test('it respects user notification preferences', function () {
    $emailAdmin = User::factory()->create(['role' => UserRole::Admin, 'notification_preference' => \App\Enums\NotificationPreference::Email]);
    $smsAdmin = User::factory()->create(['role' => UserRole::Admin, 'notification_preference' => \App\Enums\NotificationPreference::Sms]);
    $bothAdmin = User::factory()->create(['role' => UserRole::Admin, 'notification_preference' => \App\Enums\NotificationPreference::Both]);

    $booking = Booking::factory()->create();
    $notification = new BookingReceivedNotification($booking);

    expect($notification->via($emailAdmin))->toContain('mail', 'database');
    expect($notification->via($emailAdmin))->not->toContain(\App\Notifications\Channels\GaintSmsChannel::class);

    expect($notification->via($smsAdmin))->toContain('database', \App\Notifications\Channels\GaintSmsChannel::class);
    expect($notification->via($smsAdmin))->not->toContain('mail');

    expect($notification->via($bothAdmin))->toContain('mail', 'database', \App\Notifications\Channels\GaintSmsChannel::class);
});

test('notification bell unread count is updated', function () {
    $this->actingAs($this->admin);

    Livewire::test(\App\Livewire\Admin\Notifications\NotificationBell::class)
        ->assertSet('unreadCount', 0);

    $booking = Booking::factory()->create();
    $this->admin->notify(new BookingReceivedNotification($booking));

    Livewire::test(\App\Livewire\Admin\Notifications\NotificationBell::class)
        ->call('refreshCount')
        ->assertSet('unreadCount', 1);
});
