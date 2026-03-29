<?php

use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/general');

    Route::livewire('settings/profile', Profile::class)->name('profile.edit');
    Route::livewire('settings/general', \App\Livewire\Settings\General::class)->name('settings.general');
    Route::livewire('settings/notifications', \App\Livewire\Settings\Notifications::class)->name('settings.notifications');
    Route::livewire('settings/payment', \App\Livewire\Settings\Payment::class)->name('settings.payment');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('settings/password', Password::class)->name('user-password.edit');
    Route::livewire('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
