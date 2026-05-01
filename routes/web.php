<?php

use App\Enums\BookingType;
use App\Http\Controllers\Booking\TransflowReturnController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Webhooks\MoolreWebhookController;
use App\Http\Controllers\Webhooks\TransflowWebhookController;
use App\Livewire\Admin\Bookings\EventIndex;
use App\Livewire\Admin\DesignSystem;
use App\Livewire\Admin\ErrorLogs\ErrorLogIndex;
use App\Livewire\Admin\Payments\PaymentsOverview;
use App\Livewire\Admin\Reports\ReportsView;
use App\Livewire\Admin\Settings\AdminSettings;
use App\Livewire\Admin\Users\RoleIndex;
use App\Livewire\Admin\Users\UserForm;
use App\Livewire\Admin\Users\UserIndex;
use App\Livewire\Admin\Users\UserShow;
use App\Livewire\Auth\PhoneVerification;
use App\Livewire\Booking\BookingWizard;
use App\Livewire\Booking\CheckoutPayment;
use App\Livewire\Booking\EventInquiryWizard;
use App\Livewire\Booking\OfflineWaiting;
use App\Livewire\Booking\TrackBooking;
use App\Livewire\Categories\CategoryIndex;
use App\Livewire\Customer\Meals\Index;
use App\Livewire\Customer\Meals\Show;
use App\Livewire\Customer\PaymentMethods;
use App\Livewire\Customer\Profile;
use App\Livewire\Customers\CustomerForm;
use App\Livewire\Customers\CustomerIndex;
use App\Livewire\Customers\CustomerShow;
use App\Livewire\Dashboard;
use App\Livewire\Packages\PackageForm;
use App\Livewire\Packages\PackageIndex;
use App\Livewire\Packages\PackagesBrowse;
use App\Livewire\Pages\HomePage;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home');

Route::get('/invitation/accept/{token}', [InvitationController::class, 'accept'])
    ->name('invitation.accept');
Route::get('/menu', PackagesBrowse::class)->name('packages.browse');

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

Route::middleware(['auth'])
    ->get('/verify-phone', PhoneVerification::class)
    ->name('verification.phone');

Route::middleware(['auth', 'customer'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {
        Route::get('/', App\Livewire\Customer\Dashboard::class)->name('index');

        // Meal Orders
        Route::get('/meals', Index::class)->name('meals.index');
        Route::get('/meals/{booking:reference}', Show::class)->name('meals.show');

        // Events
        Route::get('/events', App\Livewire\Customer\Events\Index::class)->name('events.index');
        Route::get('/events/{booking:reference}', App\Livewire\Customer\Events\Show::class)->name('events.show');

        // Legacy redirects
        Route::get('/bookings', fn () => redirect()->route('dashboard.meals.index'))->name('bookings.index');
        Route::get('/bookings/{booking:reference}', function (Booking $booking) {
            return $booking->booking_type === BookingType::Event
                ? redirect()->route('dashboard.events.show', $booking->reference)
                : redirect()->route('dashboard.meals.show', $booking->reference);
        })->name('bookings.show');

        Route::get('/payments', App\Livewire\Customer\Payments\Index::class)->name('payments.index');
        Route::get('/payment-methods', PaymentMethods::class)->name('payment-methods');
        Route::get('/profile', Profile::class)->name('profile');
    });

// Customer Booking Flow
Route::get('/checkout', BookingWizard::class)->name('checkout');
Route::get('/event-booking', EventInquiryWizard::class)->name('event-booking');
Route::get('/booking/track', TrackBooking::class)->name('booking.track');
Route::get('/booking/payment/{booking:reference}', CheckoutPayment::class)->name('booking.payment');
Route::get('/booking/confirmation/{booking:reference}', function (Booking $booking) {
    $view = $booking->booking_type === BookingType::Event
        ? 'booking.event-confirmation'
        : 'booking.confirmation';

    return view($view, ['booking' => $booking]);
})->name('booking.confirmation');

Route::get('/booking/offline-waiting/{booking:reference}', OfflineWaiting::class)
    ->name('bookings.offline-waiting');

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', Dashboard::class)->name('dashboard');

        // Admin Bookings
        Route::get('bookings', App\Livewire\Admin\Bookings\Index::class)->name('bookings.index');
        Route::get('bookings/{booking:reference}', App\Livewire\Admin\Bookings\Show::class)->name('bookings.show');

        // Admin Events
        Route::get('events', EventIndex::class)->name('events.index');

        // Admin Packages
        Route::get('manage-packages', PackageIndex::class)->name('manage-packages.index');
        Route::get('manage-packages/create', PackageForm::class)->name('manage-packages.create');
        Route::get('manage-packages/{package:slug}/edit', PackageForm::class)->name('manage-packages.edit');

        // Admin Payments
        Route::get('payments', PaymentsOverview::class)->name('payments.index');

        // Admin Reports
        Route::get('reports', ReportsView::class)->name('reports.index');

        // Customers
        Route::get('customers', CustomerIndex::class)->name('customers.index');
        Route::get('customers/create', CustomerForm::class)->name('customers.create');
        Route::get('customers/{customer:uuid}', CustomerShow::class)->name('customers.show');
        Route::get('customers/{customer:uuid}/edit', CustomerForm::class)->name('customers.edit');

        // Categories
        Route::get('categories', CategoryIndex::class)->name('categories.index');

        // Contact Messages
        Route::get('contact-messages', App\Livewire\Admin\ContactMessages\Index::class)->name('contact-messages');

        // Admin Settings
        Route::get('settings', AdminSettings::class)->name('settings.index');

        // Error Logs (super admin + users with view_error_logs permission)
        Route::get('error-logs', ErrorLogIndex::class)->name('error-logs.index');

        // User Management
        Route::get('users', UserIndex::class)->name('users.index');
        Route::get('users/create', UserForm::class)->name('users.create');
        Route::get('users/{user:uuid}', UserShow::class)->name('users.show');
        Route::get('users/{user:uuid}/edit', UserForm::class)->name('users.edit');
        Route::get('roles', RoleIndex::class)->name('roles.index');

        // Impersonation
        Route::get('impersonate/stop', function () {
            if (session()->has('impersonator_id')) {
                Auth::loginUsingId(session()->pull('impersonator_id'));

                return redirect()->route('admin.customers.index')->with('success', 'Impersonation terminated.');
            }

            return redirect()->home();
        })->name('impersonate.stop');

        // Design System

        Route::get('design-system', DesignSystem::class)->name('design-system');
    });

// Payment Webhooks — CSRF exempt (see bootstrap/app.php)
Route::post('/webhooks/moolre', MoolreWebhookController::class)
    ->name('webhooks.moolre');

Route::post('/webhooks/transflow', TransflowWebhookController::class)
    ->name('webhooks.transflow');

// Transflow customer browser return URL (GET — no CSRF needed)
// Called after the customer completes or abandons payment on Transflow's hosted page
Route::get('/booking/payment/return/{booking:reference}', TransflowReturnController::class)
    ->name('booking.payment.return');

// Public Invoices (Signed)
Route::get('/invoice/{reference}/download', [InvoiceController::class, 'download'])
    ->name('invoice.download');

require __DIR__.'/settings.php';
