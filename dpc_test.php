<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Debugging what happens when validateStep1 runs with typical values.
$wizard = app()->make(App\Livewire\Booking\BookingWizard::class);
$wizard->name = 'Test User';
$wizard->phone = '0241234567';
try {
    $wizard->nextStep();
    echo "Step 1 OK\n";
    $wizard->nextStep();
    echo "Step 2 OK\n";
} catch (\Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
}
