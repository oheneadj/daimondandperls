<?php

declare(strict_types=1);

use App\Livewire\Admin\BookingWindows\BookingWindowIndex;
use App\Models\BookingWindow;
use App\Models\Package;
use App\Models\Role;
use App\Models\User;
use App\Services\BookingWindowService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function windowAdminUser(): User
{
    $role = Role::updateOrCreate(['slug' => 'super_admin'], ['name' => 'Super Admin', 'description' => 'Super Administrator']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

// ─── Model & Relationships ────────────────────────────────────────────────────

it('booking window belongs to many packages', function () {
    $window = BookingWindow::factory()->friday()->create();
    $package = Package::factory()->create();

    $window->packages()->attach($package);

    expect($window->packages()->count())->toBe(1);
    expect($package->bookingWindows()->count())->toBe(1);
});

it('package with no booking windows is always bookable', function () {
    $package = Package::factory()->create();
    $service = app(BookingWindowService::class);

    expect($service->getActiveWindow($package))->toBeNull();
    expect($service->getScheduledDeliveryForPackage($package))->toBeNull();
});

// ─── BookingWindowService ─────────────────────────────────────────────────────

it('getActiveWindow returns the window with the soonest upcoming cutoff', function () {
    Carbon::setTestNow(Carbon::parse('2024-06-10 08:00:00')); // Monday

    // Friday window: cutoff Wednesday 10:00 — next cutoff is Wed Jun 12
    $friday = BookingWindow::factory()->friday()->create();
    // Tuesday window: cutoff Sunday 10:00 — next cutoff is Sun Jun 16
    $tuesday = BookingWindow::factory()->tuesday()->create();

    $package = Package::factory()->create();
    $package->bookingWindows()->attach([$friday->id, $tuesday->id]);
    $package->load('bookingWindows');

    $service = app(BookingWindowService::class);
    $active = $service->getActiveWindow($package);

    expect($active)->not->toBeNull();
    expect($active->id)->toBe($friday->id);

    Carbon::setTestNow();
});

it('active window rotates after cutoff passes', function () {
    // Wednesday 11:00 — after Wednesday 10:00 cutoff for the friday window
    Carbon::setTestNow(Carbon::parse('2024-06-12 11:00:00'));

    $friday = BookingWindow::factory()->friday()->create();
    $tuesday = BookingWindow::factory()->tuesday()->create();

    $package = Package::factory()->create();
    $package->bookingWindows()->attach([$friday->id, $tuesday->id]);
    $package->load('bookingWindows');

    $service = app(BookingWindowService::class);
    $active = $service->getActiveWindow($package);

    // Friday window cutoff passed; next upcoming cutoff is Sunday (tuesday window)
    expect($active->id)->toBe($tuesday->id);

    Carbon::setTestNow();
});

it('getScheduledDeliveryForPackage returns a date when windows exist', function () {
    $window = BookingWindow::factory()->friday()->create();
    $package = Package::factory()->create();
    $package->bookingWindows()->attach($window);
    $package->load('bookingWindows');

    $service = app(BookingWindowService::class);
    $date = $service->getScheduledDeliveryForPackage($package);

    expect($date)->not->toBeNull();
    expect($date->dayOfWeekIso)->toBe(5); // Friday
});

// ─── Admin CRUD ───────────────────────────────────────────────────────────────

it('admin can view booking windows index', function () {
    $admin = windowAdminUser();

    Livewire::actingAs($admin)
        ->test(BookingWindowIndex::class)
        ->assertOk();
});

it('admin can create a booking window', function () {
    $admin = windowAdminUser();

    Livewire::actingAs($admin)
        ->test(BookingWindowIndex::class)
        ->call('openCreateModal')
        ->set('name', 'Friday Lunch')
        ->set('delivery_day', 5)
        ->set('cutoff_day', 3)
        ->set('cutoff_time', '10:00')
        ->call('saveWindow')
        ->assertHasNoErrors();

    expect(BookingWindow::where('name', 'Friday Lunch')->exists())->toBeTrue();
});

it('admin can edit a booking window', function () {
    $admin = windowAdminUser();
    $window = BookingWindow::factory()->friday()->create();

    Livewire::actingAs($admin)
        ->test(BookingWindowIndex::class)
        ->call('openEditModal', $window->id)
        ->set('name', 'Updated Window')
        ->call('saveWindow')
        ->assertHasNoErrors();

    expect($window->fresh()->name)->toBe('Updated Window');
});

it('admin can delete a booking window', function () {
    $admin = windowAdminUser();
    $window = BookingWindow::factory()->friday()->create();

    Livewire::actingAs($admin)
        ->test(BookingWindowIndex::class)
        ->call('confirmDelete', $window->id)
        ->call('deleteWindow');

    expect(BookingWindow::find($window->id))->toBeNull();
});

it('booking window validation requires name, delivery day, cutoff day and time', function () {
    $admin = windowAdminUser();

    Livewire::actingAs($admin)
        ->test(BookingWindowIndex::class)
        ->call('openCreateModal')
        ->call('saveWindow')
        ->assertHasErrors(['name', 'delivery_day', 'cutoff_day', 'cutoff_time']);
});

// ─── Package-window attachment ────────────────────────────────────────────────

it('package with one window shows correct delivery day', function () {
    $window = BookingWindow::factory()->friday()->create();
    $package = Package::factory()->create();
    $package->bookingWindows()->attach($window);
    $package->load('bookingWindows');

    $service = app(BookingWindowService::class);
    $date = $service->getScheduledDeliveryForPackage($package);

    expect($date)->not->toBeNull();
    expect($date->dayOfWeekIso)->toBe(5);
});

it('package with two windows activates the next upcoming cutoff', function () {
    Carbon::setTestNow(Carbon::parse('2024-06-10 08:00:00')); // Monday

    $friday = BookingWindow::factory()->friday()->create();
    $tuesday = BookingWindow::factory()->tuesday()->create();

    $package = Package::factory()->create();
    $package->bookingWindows()->attach([$friday->id, $tuesday->id]);
    $package->load('bookingWindows');

    $service = app(BookingWindowService::class);
    $date = $service->getScheduledDeliveryForPackage($package);

    // Active window is friday (soonest cutoff = this Wednesday)
    expect($date->dayOfWeekIso)->toBe(5);

    Carbon::setTestNow();
});
