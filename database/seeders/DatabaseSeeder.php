<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Enums\PaymentGateway;
use App\Enums\PaymentGatewayStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'role' => \App\Enums\UserRole::SuperAdmin,
        ]);

        $this->call([
            RolesAndPermissionsSeeder::class,
            SettingsSeeder::class,
            CateringPackageSeeder::class,
        ]);

        \App\Models\Customer::factory(10)->create();

        // Spread bookings across realistic statuses
        $statusMix = [
            ['status' => BookingStatus::Confirmed, 'payment_status' => PaymentStatus::Paid],
            ['status' => BookingStatus::Confirmed, 'payment_status' => PaymentStatus::Paid],
            ['status' => BookingStatus::Confirmed, 'payment_status' => PaymentStatus::Paid],
            ['status' => BookingStatus::Confirmed, 'payment_status' => PaymentStatus::Paid],
            ['status' => BookingStatus::Confirmed, 'payment_status' => PaymentStatus::Paid],
            ['status' => BookingStatus::InPreparation, 'payment_status' => PaymentStatus::Paid],
            ['status' => BookingStatus::InPreparation, 'payment_status' => PaymentStatus::Paid],
            ['status' => BookingStatus::Completed, 'payment_status' => PaymentStatus::Paid],
            ['status' => BookingStatus::Completed, 'payment_status' => PaymentStatus::Paid],
            ['status' => BookingStatus::Completed, 'payment_status' => PaymentStatus::Paid],
            ['status' => BookingStatus::Pending, 'payment_status' => PaymentStatus::Unpaid],
            ['status' => BookingStatus::Pending, 'payment_status' => PaymentStatus::Unpaid],
            ['status' => BookingStatus::Pending, 'payment_status' => PaymentStatus::Unpaid],
            ['status' => BookingStatus::Pending, 'payment_status' => PaymentStatus::Pending],
            ['status' => BookingStatus::Pending, 'payment_status' => PaymentStatus::Pending],
            ['status' => BookingStatus::Confirmed, 'payment_status' => PaymentStatus::Unpaid],
            ['status' => BookingStatus::Confirmed, 'payment_status' => PaymentStatus::Pending],
            ['status' => BookingStatus::Cancelled, 'payment_status' => PaymentStatus::Unpaid],
            ['status' => BookingStatus::Cancelled, 'payment_status' => PaymentStatus::Refunded],
            ['status' => BookingStatus::ReadyForDelivery, 'payment_status' => PaymentStatus::Paid],
        ];

        foreach ($statusMix as $state) {
            $booking = Booking::factory()->create($state);

            // Create a matching Payment record for paid bookings
            if ($state['payment_status'] === PaymentStatus::Paid) {
                Payment::create([
                    'booking_id' => $booking->id,
                    'gateway' => PaymentGateway::Moolre,
                    'method' => PaymentMethod::MobileMoney,
                    'gateway_reference' => 'SEED-'.strtoupper(substr(md5($booking->id.time()), 0, 10)),
                    'amount' => $booking->total_amount,
                    'currency' => 'GHS',
                    'status' => PaymentGatewayStatus::Successful,
                    'paid_at' => now()->subDays(rand(1, 30)),
                ]);
            }

            // Create a pending payment record for MoMo-initiated but not yet completed
            if ($state['payment_status'] === PaymentStatus::Pending) {
                Payment::create([
                    'booking_id' => $booking->id,
                    'gateway' => PaymentGateway::Moolre,
                    'method' => PaymentMethod::MobileMoney,
                    'gateway_reference' => 'SEED-'.strtoupper(substr(md5($booking->id.time()), 0, 10)),
                    'amount' => $booking->total_amount,
                    'currency' => 'GHS',
                    'status' => PaymentGatewayStatus::Pending,
                    'paid_at' => null,
                ]);
            }
        }
    }
}
