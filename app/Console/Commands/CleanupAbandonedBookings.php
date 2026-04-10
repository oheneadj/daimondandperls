<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use Illuminate\Console\Command;

class CleanupAbandonedBookings extends Command
{
    protected $signature = 'booking:cleanup-abandoned
                            {--hours=24 : Hours after which unpaid bookings are cancelled}';

    protected $description = 'Cancel pending bookings that have not been paid within the specified time window';

    public function handle(): int
    {
        $hours = (int) $this->option('hours');

        $cancelled = Booking::query()
            ->where('status', BookingStatus::Pending)
            ->where('payment_status', PaymentStatus::Unpaid)
            ->where('created_at', '<', now()->subHours($hours))
            ->update([
                'status' => BookingStatus::Cancelled,
                'cancelled_at' => now(),
                'cancelled_reason' => "Automatically cancelled — no payment received within {$hours} hours",
            ]);

        $this->info("Cancelled {$cancelled} abandoned booking(s).");

        return self::SUCCESS;
    }
}
