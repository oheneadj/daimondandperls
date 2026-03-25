<?php

declare(strict_types=1);

use App\Enums\PaymentGateway;
use App\Enums\PaymentGatewayStatus;
use App\Enums\PaymentMethod;
use App\Livewire\Admin\Payments\PaymentsOverview;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create();
    $this->customer = Customer::factory()->create();
    $this->booking = Booking::factory()->create([
        'customer_id' => $this->customer->id,
    ]);
});

it('allows admin to view the payments page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.payments.index'))
        ->assertOk()
        ->assertSeeLivewire(PaymentsOverview::class)
        ->assertSee('Payments Overview');
});

it('requires authentication to view the payments page', function () {
    $this->get(route('admin.payments.index'))
        ->assertRedirect(route('login'));
});

it('displays payments in the list', function () {
    $payment = Payment::factory()->create([
        'booking_id' => $this->booking->id,
        'amount' => 100.00,
        'currency' => 'GH₵',
        'status' => PaymentGatewayStatus::Successful,
        'method' => PaymentMethod::MobileMoney,
        'gateway' => PaymentGateway::Paystack,
    ]);

    $this->actingAs($this->admin);
    Livewire::test(PaymentsOverview::class)
        ->assertSee($payment->amount)
        ->assertSee($payment->currency)
        ->assertSee(match($payment->status->value) {
            'successful' => 'Paid',
            'pending' => 'Pending',
            'failed' => 'Failed',
            default => 'Unknown'
        })
        ->assertSee(str_replace('_', ' ', $payment->method->value));
});

it('filters payments by status', function () {
    $payment1 = Payment::factory()->create([
        'booking_id' => $this->booking->id,
        'status' => PaymentGatewayStatus::Successful,
        'amount' => 200,
    ]);

    $booking2 = Booking::factory()->create(['customer_id' => $this->customer->id]);
    $payment2 = Payment::factory()->create([
        'booking_id' => $booking2->id,
        'status' => PaymentGatewayStatus::Pending,
        'amount' => 300,
    ]);

    $this->actingAs($this->admin);
    Livewire::test(PaymentsOverview::class)
        ->set('activeTab', 'paid')
        ->assertSee($payment1->booking->reference)
        ->assertDontSee($payment2->booking->reference)
        ->set('activeTab', 'pending')
        ->assertSee($payment2->booking->reference)
        ->assertDontSee($payment1->booking->reference);
});

it('allows admin to manually verify a pending manual payment', function () {
    $payment = Payment::factory()->create([
        'booking_id' => $this->booking->id,
        'status' => PaymentGatewayStatus::Pending,
        'gateway' => PaymentGateway::Manual,
        'method' => PaymentMethod::BankTransfer,
        'amount' => 500,
    ]);

    $this->actingAs($this->admin);
    Livewire::test(PaymentsOverview::class)
        ->call('confirmVerify', $payment->id)
        ->call('verifyPayment');

    $payment->refresh();
    expect($payment->status)->toBe(PaymentGatewayStatus::Successful)
        ->and($payment->verified_by)->toBe($this->admin->id)
        ->and($payment->verified_at)->not->toBeNull();
});

it('prevents manual verification of non-manual payments', function () {
    $payment = Payment::factory()->create([
        'booking_id' => $this->booking->id,
        'status' => PaymentGatewayStatus::Pending,
        'gateway' => PaymentGateway::Paystack,
        'method' => PaymentMethod::MobileMoney, // Not manual
    ]);

    $this->actingAs($this->admin);
    Livewire::test(PaymentsOverview::class)
        ->call('confirmVerify', $payment->id)
        ->assertDispatched('notify', message: 'This payment cannot be manually verified.', type: 'error');

    $payment->refresh();
    expect($payment->status)->toBe(PaymentGatewayStatus::Pending)
        ->and($payment->verified_by)->toBeNull();
});
