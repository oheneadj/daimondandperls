<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class InvoiceService
{
    /**
     * Generate a PDF invoice for a booking and store it on disk.
     */
    public function generate(Booking $booking): string
    {
        $booking->loadMissing(['customer', 'items.package', 'payment']);

        $data = [
            'booking' => $booking,
            'company' => [
                'name' => dpc_setting('business_name') ?? config('app.name'),
                'address' => dpc_setting('business_address', ''),
                'phone' => dpc_setting('business_phone', ''),
                'email' => dpc_setting('business_email', ''),
                'logo' => dpc_setting('business_logo', ''),
            ],
            'bank' => [
                'name' => dpc_setting('bank_name', ''),
                'account_name' => dpc_setting('account_name', ''),
                'account_number' => dpc_setting('account_number', ''),
                'branch_code' => dpc_setting('branch_code', ''),
            ],
        ];

        $view = $booking->booking_type === \App\Enums\BookingType::Event
            ? 'pdf.invoice-event'
            : 'pdf.invoice-meal';

        $pdf = Pdf::loadView($view, $data);
        $pdf->setPaper('a4');

        $path = 'invoices/'.$booking->reference.'.pdf';

        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

    /**
     * Get a signed download URL for a booking invoice (valid for 30 days).
     */
    public function getDownloadUrl(Booking $booking): string
    {
        return URL::signedRoute('invoice.download', [
            'reference' => $booking->reference,
        ], now()->addDays(30));
    }

    /**
     * Check if an invoice PDF exists for a booking.
     */
    public function exists(Booking $booking): bool
    {
        return Storage::disk('public')->exists('invoices/'.$booking->reference.'.pdf');
    }
}
