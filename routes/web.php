<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Pages\HomePage::class)->name('home');

Route::get('/invitation/accept/{token}', [\App\Http\Controllers\InvitationController::class, 'accept'])
    ->name('invitation.accept');
Route::get('/all-packages', \App\Livewire\Packages\PackagesBrowse::class)->name('packages.browse');

Route::get('/about', function () {
    return view('public.about');
})->name('about');
Route::get('/contact', function () {
    return view('public.contact');
})->name('contact');
Route::get('/terms', function () {
    return view('public.terms');
})->name('terms');
Route::get('/privacy', function () {
    return view('public.privacy');
})->name('privacy');

Route::middleware(['auth', 'customer'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {
        Route::get('/', \App\Livewire\Customer\Dashboard::class)->name('index');

        // Meal Orders
        Route::get('/meals', \App\Livewire\Customer\Meals\Index::class)->name('meals.index');
        Route::get('/meals/{booking:reference}', \App\Livewire\Customer\Meals\Show::class)->name('meals.show');

        // Events
        Route::get('/events', \App\Livewire\Customer\Events\Index::class)->name('events.index');
        Route::get('/events/{booking:reference}', \App\Livewire\Customer\Events\Show::class)->name('events.show');

        // Legacy redirects
        Route::get('/bookings', fn () => redirect()->route('dashboard.meals.index'))->name('bookings.index');
        Route::get('/bookings/{booking:reference}', function (\App\Models\Booking $booking) {
            return $booking->booking_type === \App\Enums\BookingType::Event
                ? redirect()->route('dashboard.events.show', $booking->reference)
                : redirect()->route('dashboard.meals.show', $booking->reference);
        })->name('bookings.show');

        Route::get('/payments', \App\Livewire\Customer\Payments\Index::class)->name('payments.index');
        Route::get('/payment-methods', \App\Livewire\Customer\PaymentMethods::class)->name('payment-methods');
        Route::get('/profile', \App\Livewire\Customer\Profile::class)->name('profile');
    });

// Customer Booking Flow
Route::get('/checkout', App\Livewire\Booking\BookingWizard::class)->name('checkout');
Route::get('/event-booking', App\Livewire\Booking\EventInquiryWizard::class)->name('event-booking');
Route::get('/booking/track', App\Livewire\Booking\TrackBooking::class)->name('booking.track');
Route::get('/booking/payment/{booking:reference}', App\Livewire\Booking\CheckoutPayment::class)->name('booking.payment');
Route::get('/booking/confirmation/{booking:reference}', function (\App\Models\Booking $booking) {
    $view = $booking->booking_type === \App\Enums\BookingType::Event
        ? 'booking.event-confirmation'
        : 'booking.confirmation';

    return view($view, ['booking' => $booking]);
})->name('booking.confirmation');

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', \App\Livewire\Dashboard::class)->name('dashboard');

        // Admin Bookings
        Route::get('bookings', \App\Livewire\Admin\Bookings\Index::class)->name('bookings.index');
        Route::get('bookings/{booking:reference}', \App\Livewire\Admin\Bookings\Show::class)->name('bookings.show');

        // Admin Events
        Route::get('events', \App\Livewire\Admin\Bookings\EventIndex::class)->name('events.index');

        // Admin Packages
        Route::get('manage-packages', \App\Livewire\Packages\PackageIndex::class)->name('manage-packages.index');
        Route::get('manage-packages/create', \App\Livewire\Packages\PackageForm::class)->name('manage-packages.create');
        Route::get('manage-packages/{package:slug}/edit', \App\Livewire\Packages\PackageForm::class)->name('manage-packages.edit');

        // Admin Payments
        Route::get('payments', \App\Livewire\Admin\Payments\PaymentsOverview::class)->name('payments.index');

        // Admin Reports
        Route::get('reports', \App\Livewire\Admin\Reports\ReportsView::class)->name('reports.index');

        // Customers
        Route::get('customers', \App\Livewire\Customers\CustomerIndex::class)->name('customers.index');
        Route::get('customers/create', \App\Livewire\Customers\CustomerForm::class)->name('customers.create');
        Route::get('customers/{customer:uuid}', \App\Livewire\Customers\CustomerShow::class)->name('customers.show');
        Route::get('customers/{customer:uuid}/edit', \App\Livewire\Customers\CustomerForm::class)->name('customers.edit');

        // Categories
        Route::get('categories', \App\Livewire\Categories\CategoryIndex::class)->name('categories.index');

        // Admin Settings
        Route::get('settings', \App\Livewire\Admin\Settings\AdminSettings::class)->name('settings.index');

        // Error Logs (super admin + users with view_error_logs permission)
        Route::get('error-logs', \App\Livewire\Admin\ErrorLogs\ErrorLogIndex::class)->name('error-logs.index');

        // User Management
        Route::get('users', \App\Livewire\Admin\Users\UserIndex::class)->name('users.index');
        Route::get('users/create', \App\Livewire\Admin\Users\UserForm::class)->name('users.create');
        Route::get('users/{user:uuid}', \App\Livewire\Admin\Users\UserShow::class)->name('users.show');
        Route::get('users/{user:uuid}/edit', \App\Livewire\Admin\Users\UserForm::class)->name('users.edit');
        Route::get('roles', \App\Livewire\Admin\Users\RoleIndex::class)->name('roles.index');

        // Impersonation
        Route::get('impersonate/stop', function () {
            if (session()->has('impersonator_id')) {
                \Illuminate\Support\Facades\Auth::loginUsingId(session()->pull('impersonator_id'));

                return redirect()->route('admin.customers.index')->with('success', 'Impersonation terminated.');
            }

            return redirect()->home();
        })->name('impersonate.stop');

        // Design System

        Route::get('design-system', \App\Livewire\Admin\DesignSystem::class)->name('design-system');
    });

// Payment Webhooks — CSRF exempt (see bootstrap/app.php)
Route::post('/webhooks/moolre', \App\Http\Controllers\Webhooks\MoolreWebhookController::class)
    ->name('webhooks.moolre');

Route::post('/webhooks/transflow', \App\Http\Controllers\Webhooks\TransflowWebhookController::class)
    ->name('webhooks.transflow');

// Transflow customer browser return URL (GET — no CSRF needed)
// Called after the customer completes or abandons payment on Transflow's hosted page
Route::get('/booking/payment/return/{booking:reference}', \App\Http\Controllers\Booking\TransflowReturnController::class)
    ->name('booking.payment.return');

// Public Invoices (Signed)
Route::get('/invoice/{reference}/download', [\App\Http\Controllers\InvoiceController::class, 'download'])
    ->name('invoice.download');

require __DIR__.'/settings.php';
