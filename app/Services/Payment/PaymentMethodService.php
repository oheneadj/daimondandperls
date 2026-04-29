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
    public function saveFromBooking(Booking $booking, ?string $overrideMsisdn = null, ?string $overrideNetwork = null): void
    {
        // I use the provided number/network (usually from a webhook), or fall back to the booking data.
        $msisdn = $overrideMsisdn ?: $booking->payer_number;
        $network = $overrideNetwork ?: $booking->payment_channel;

        // If I still don't have a number or network, I can't save anything.
        if (empty($msisdn) || empty($network)) {
            return;
        }

        // I don't save card details to the MoMo list.
        if (strtoupper((string) $network) === 'CARD') {
            return;
        }

        // I only want to save payment methods for registered users.
        $customer = $booking->customer;
        if (! $customer || ! $customer->user_id) {
            return;
        }

        // I map the network (either ID or name) to a friendly name for the label.
        $networkName = match (strtoupper((string) $network)) {
            '13', 'MTN' => 'MTN MoMo',
            '6', 'VODAFONE', 'TELECEL' => 'Telecel Cash',
            '7', 'AIRTELTIGO' => 'AirtelTigo Money',
            default => 'Mobile Money',
        };

        // If this is their first saved method, I make it the default one.
        $isFirst = $customer->paymentMethods()->count() === 0;

        // I use updateOrCreate to avoid making duplicates.
        CustomerPaymentMethod::updateOrCreate(
            [
                'customer_id' => $customer->id,
                'account_number' => $msisdn,
            ],
            [
                'type' => PaymentMethod::MobileMoney->value,
                'label' => $networkName.' - '.$msisdn,
                'provider' => $network,
                'is_default' => $isFirst,
                'verified_at' => now(),
            ]
        );

        Log::info('PaymentMethodService: Saved MoMo number for customer', [
            'customer' => $customer->id,
            'number' => $msisdn,
        ]);
    }
}
