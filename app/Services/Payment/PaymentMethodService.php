<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Enums\PaymentMethod;
use App\Models\Booking;
use App\Models\CustomerPaymentMethod;
use Illuminate\Support\Facades\Log;

class PaymentMethodService
{
    /**
     * I use this to save a new MoMo number to a customer's profile after they pay.
     * I only save it if they are logged in and actually entered a new number.
     */
    public function saveFromBooking(Booking $booking): void
    {
        // If there is no phone number or channel saved on the booking, I can't do anything.
        if (empty($booking->payer_number) || empty($booking->payment_channel)) {
            return;
        }

        // I only want to save payment methods for registered users.
        $customer = $booking->customer;
        if (! $customer || ! $customer->user_id) {
            return;
        }

        // I map the network ID to a friendly name for the label.
        $networkName = match ($booking->payment_channel) {
            '13' => 'MTN MoMo',
            '6' => 'Telecel Cash',
            '7' => 'AirtelTigo Money',
            default => 'Mobile Money',
        };

        // If this is their first saved method, I make it the default one.
        $isFirst = $customer->paymentMethods()->count() === 0;

        // I use updateOrCreate to avoid making duplicates if the webhook and redirect both run.
        CustomerPaymentMethod::updateOrCreate(
            [
                'customer_id' => $customer->id,
                'account_number' => $booking->payer_number,
            ],
            [
                'type' => PaymentMethod::MobileMoney->value,
                'label' => $networkName.' - '.$booking->payer_number,
                'provider' => $booking->payment_channel,
                'is_default' => $isFirst,
                'verified_at' => now(), // It's verified because they just paid with it successfully.
            ]
        );

        Log::info('PaymentMethodService: Saved MoMo number for customer', [
            'customer' => $customer->id,
            'number' => $booking->payer_number,
        ]);
    }
}
