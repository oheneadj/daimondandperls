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

        $settings = \App\Models\Setting::whereIn('group', ['business', 'bank'])
            ->get()
            ->keyBy('key');

        $data = [
            'booking' => $booking,
            'company' => [
                'name' => $settings->get('business_name')?->value ?? config('app.name'),
                'address' => $settings->get('business_address')?->value ?? '',
                'phone' => $settings->get('business_phone')?->value ?? '',
                'email' => $settings->get('business_email')?->value ?? '',
                'logo' => $settings->get('business_logo')?->value ?? '',
            ],
            'bank' => [
                'name' => $settings->get('bank_name')?->value ?? '',
                'account_name' => $settings->get('account_name')?->value ?? '',
                'account_number' => $settings->get('account_number')?->value ?? '',
                'branch_code' => $settings->get('branch_code')?->value ?? '',
            ],
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data);
        $pdf->setPaper('a4');

        $path = 'invoices/' . $booking->reference . '.pdf';

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
        return Storage::disk('public')->exists('invoices/' . $booking->reference . '.pdf');
    }
}
