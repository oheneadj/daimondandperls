<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Enums\PaymentGateway;
use App\Enums\PaymentGatewayStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\ContactMessage;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TestingLargeDatasetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! app()->isLocal()) {
            $this->command->error('This seeder can only be run in the local environment.');

            return;
        }

        $this->command->warn('Starting large dataset seeding...');

        DB::disableQueryLog();

        $this->seedPackages(50);
        $this->seedCustomers(5000);
        $this->seedBookingsAndPayments(20000);
        $this->seedContactMessages(2000);

        $this->command->info('Large dataset seeding completed successfully.');
    }

    private function seedPackages(int $total): void
    {
        $currentCount = Package::count();
        if ($currentCount >= $total) {
            return;
        }

        $needed = $total - $currentCount;
        $this->command->info("Seeding {$needed} packages...");
        Package::factory()->count($needed)->create();
    }

    private function seedCustomers(int $total): void
    {
        $this->command->info("Seeding {$total} customers...");

        $batchSize = 500;
        $batches = ceil($total / $batchSize);

        $bar = $this->command->getOutput()->createProgressBar((int) $batches);
        $bar->start();

        for ($i = 0; $i < $batches; $i++) {
            Customer::factory()->count($batchSize)->create();
            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
    }

    private function seedBookingsAndPayments(int $total): void
    {
        $this->command->info("Seeding {$total} bookings and corresponding payments...");

        $batchSize = 500;
        $batches = ceil($total / $batchSize);

        $customerIds = Customer::pluck('id')->toArray();
        if (empty($customerIds)) {
            $this->command->error('No customers found to attach bookings to.');

            return;
        }

        $packages = Package::all();
        if ($packages->isEmpty()) {
            $this->command->error('No packages found to attach to bookings.');

            return;
        }

        $bar = $this->command->getOutput()->createProgressBar((int) $batches);
        $bar->start();

        $statusMix = [
            ['status' => BookingStatus::Confirmed,        'payment_status' => PaymentStatus::Paid],
            ['status' => BookingStatus::InPreparation,    'payment_status' => PaymentStatus::Paid],
            ['status' => BookingStatus::Completed,        'payment_status' => PaymentStatus::Paid],
            ['status' => BookingStatus::Pending,          'payment_status' => PaymentStatus::Unpaid],
            ['status' => BookingStatus::Pending,          'payment_status' => PaymentStatus::Pending],
            ['status' => BookingStatus::Cancelled,        'payment_status' => PaymentStatus::Refunded],
            ['status' => BookingStatus::ReadyForDelivery, 'payment_status' => PaymentStatus::Paid],
        ];

        for ($i = 0; $i < $batches; $i++) {
            // Generate models without saving to DB yet to adjust properties
            $bookings = Booking::factory()->count($batchSize)->make()->map(function ($booking) use ($customerIds, $statusMix) {
                // Random customer
                $booking->customer_id = $customerIds[array_rand($customerIds)];

                // Random booking type (Meal or Event)
                $booking->booking_type = rand(0, 1) ? BookingType::Meal : BookingType::Event;

                // Random date within the last 6 months
                $randomDays = rand(0, 180);
                $eventDate = Carbon::now()->subDays($randomDays);
                $booking->event_date = $eventDate->format('Y-m-d');
                $booking->created_at = $eventDate->copy()->subDays(rand(1, 14));
                $booking->updated_at = $booking->created_at;

                // Ensure unique reference
                $booking->reference = 'CAT-'.$eventDate->year.'-'.strtoupper(Str::random(8));

                // Set status based on the date
                if ($eventDate->isPast()) {
                    // Past events are mostly completed or cancelled
                    $state = $statusMix[array_rand([2, 5])]; // Indexes for Completed or Cancelled
                    if (rand(1, 100) <= 80) {
                        $booking->status = BookingStatus::Completed;
                        $booking->payment_status = PaymentStatus::Paid;
                    } else {
                        $booking->status = BookingStatus::Cancelled;
                        $booking->payment_status = PaymentStatus::Refunded;
                    }
                } else {
                    // Future events use a random mix
                    $state = $statusMix[array_rand($statusMix)];
                    $booking->status = $state['status'];
                    $booking->payment_status = $state['payment_status'];
                }

                return $booking->toArray();
            });

            // Insert bookings
            Booking::insert($bookings->toArray());

            // Retrieve the newly inserted bookings to attach payments (by checking reference)
            // It's faster to just generate the payments based on what we just created.
            $references = $bookings->pluck('reference')->toArray();
            $insertedBookings = Booking::whereIn('reference', $references)->get();

            $payments = [];
            $bookingItems = [];

            foreach ($insertedBookings as $booking) {
                // Attach 1 to 3 random packages
                $numItems = rand(1, 3);
                $selectedPackages = $packages->random($numItems);

                foreach ($selectedPackages as $package) {
                    $quantity = rand(1, 5);
                    $bookingItems[] = [
                        'booking_id' => $booking->id,
                        'package_id' => $package->id,
                        'package_name' => $package->name,
                        'package_description' => $package->description,
                        'price' => $package->price,
                        'quantity' => $quantity,
                        'scheduled_date' => $booking->event_date,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if ($booking->payment_status === PaymentStatus::Paid || $booking->payment_status === PaymentStatus::Refunded) {
                    $payments[] = [
                        'booking_id' => $booking->id,
                        'gateway' => PaymentGateway::Moolre->value,
                        'method' => PaymentMethod::MobileMoney->value,
                        'gateway_reference' => 'SEED-'.strtoupper(substr(md5($booking->id.time()), 0, 10)),
                        'amount' => $booking->total_amount,
                        'currency' => 'GHS',
                        'status' => PaymentGatewayStatus::Successful->value,
                        'paid_at' => $booking->created_at->addMinutes(rand(1, 60)),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } elseif ($booking->payment_status === PaymentStatus::Pending) {
                    $payments[] = [
                        'booking_id' => $booking->id,
                        'gateway' => PaymentGateway::Moolre->value,
                        'method' => PaymentMethod::MobileMoney->value,
                        'gateway_reference' => 'SEED-'.strtoupper(substr(md5($booking->id.time()), 0, 10)),
                        'amount' => $booking->total_amount,
                        'currency' => 'GHS',
                        'status' => PaymentGatewayStatus::Pending->value,
                        'paid_at' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (! empty($payments)) {
                Payment::insert($payments);
            }
            if (! empty($bookingItems)) {
                BookingItem::insert($bookingItems);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
    }

    private function seedContactMessages(int $total): void
    {
        $this->command->info("Seeding {$total} contact messages...");

        $batchSize = 500;
        $batches = ceil($total / $batchSize);

        $bar = $this->command->getOutput()->createProgressBar((int) $batches);
        $bar->start();

        for ($i = 0; $i < $batches; $i++) {
            $messages = ContactMessage::factory()->count($batchSize)->make()->map(function ($msg) {
                $randomDays = rand(0, 180);
                $createdAt = Carbon::now()->subDays($randomDays);
                $msg->created_at = $createdAt;
                $msg->updated_at = $createdAt;

                // Make some of them responded
                if (rand(1, 100) <= 60) {
                    $msg->status = 'resolved';
                    $msg->responded_at = $createdAt->copy()->addDays(rand(1, 3));
                    $msg->response_notes = 'Handled by support team.';
                }

                return $msg->toArray();
            });

            ContactMessage::insert($messages->toArray());

            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
    }
}
