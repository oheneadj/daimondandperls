<?php

use App\Models\Booking;
use App\Models\Customer;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

beforeEach(function () {
    Storage::fake('public');
    $this->customer = Customer::factory()->create();
    $this->booking = Booking::factory()->create([
        'customer_id' => $this->customer->id,
        'reference' => 'TEST-INV-001',
    ]);

    // Seed settings for testing
    \App\Models\Setting::create(['key' => 'business_name', 'value' => 'DPC Events', 'group' => 'business', 'type' => 'string']);
    \App\Models\Setting::create(['key' => 'business_address', 'value' => 'Accra, Ghana', 'group' => 'business', 'type' => 'string']);
});

it('generates a pdf invoice file', function () {
    $service = new InvoiceService();
    $path = $service->generate($this->booking);

    expect($path)->toBe('invoices/TEST-INV-001.pdf');
    Storage::disk('public')->assertExists($path);
});

it('generates a valid signed download url', function () {
    $service = new InvoiceService();
    $url = $service->getDownloadUrl($this->booking);

    expect($url)->toContain('invoice/TEST-INV-001/download');
    expect($url)->toContain('signature=');
});

it('downloads the invoice via signed route', function () {
    $service = new InvoiceService();
    $service->generate($this->booking);
    
    $url = $service->getDownloadUrl($this->booking);

    $this->get($url)
        ->assertStatus(200)
        ->assertHeader('Content-Type', 'application/pdf')
        ->assertHeader('Content-Disposition', 'attachment; filename=Invoice-TEST-INV-001.pdf');
});

it('forbids downloading invoice with invalid signature', function () {
    $url = route('invoice.download', ['reference' => $this->booking->reference]) . '?signature=invalid';

    $this->get($url)->assertStatus(403);
});

it('regenerates invoice if missing during download', function () {
    Storage::disk('public')->assertMissing('invoices/TEST-INV-001.pdf');
    
    $url = URL::signedRoute('invoice.download', [
        'reference' => $this->booking->reference,
    ], now()->addHour());

    $this->get($url)->assertStatus(200);
    
    Storage::disk('public')->assertExists('invoices/TEST-INV-001.pdf');
});

it('handles null event details gracefully', function () {
    $booking = Booking::factory()->create([
        'customer_id' => $this->customer->id,
        'reference' => 'NULL-EVENT-001',
        'event_date' => null,
        'event_start_time' => null,
        'event_end_time' => null,
    ]);

    $service = new InvoiceService();
    $path = $service->generate($booking);

    expect($path)->toBe('invoices/NULL-EVENT-001.pdf');
    Storage::disk('public')->assertExists($path);
});
