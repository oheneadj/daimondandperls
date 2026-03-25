<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$category = App\Models\Category::factory()->create();
$package1 = App\Models\Package::factory()->create(['category_id' => $category->id, 'price' => 500]);
app(App\Services\CartService::class)->add($package1->id, 1);

$wizard = Livewire\Livewire::test(App\Livewire\Booking\BookingWizard::class)
    ->set('name', 'John Doe Test')
    ->set('phone', '0241234567')
    ->set('email', 'john@test.com')
    ->call('nextStep')
    ->set('event_date', now()->addDays(5)->format('Y-m-d'))
    ->set('event_start_time', '14:00')
    ->set('event_end_time', '18:00')
    ->set('event_type', 'wedding')
    ->call('nextStep')
    ->call('confirmBooking');

echo 'Redirect URL: '.$wizard->effects['redirect']."\n";
