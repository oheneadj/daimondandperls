<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $category = App\Models\Category::first() ?? App\Models\Category::factory()->create();
    $package1 = App\Models\Package::first() ?? App\Models\Package::factory()->create(['category_id' => $category->id, 'price' => 500]);
    app(App\Services\CartService::class)->add($package1->id, 1);

    $wizard = app()->make(App\Livewire\Booking\BookingWizard::class);
    $wizard->currentStep = 3;
    $wizard->name = 'Test User';
    $wizard->phone = '0241234567';
    $wizard->email = 'test@example.com';
    $wizard->event_date = null;
    $wizard->event_start_time = null;
    $wizard->event_end_time = null;
    $wizard->event_type = null;

    $result = $wizard->confirmBooking(app(App\Services\CartService::class));
    var_dump($result->getTargetUrl());
} catch (\Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
}
