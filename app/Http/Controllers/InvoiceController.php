<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Download the invoice PDF for a booking (signed route).
     */
    public function download(Request $request, string $reference, InvoiceService $invoiceService): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        // Allow access if signature is valid OR if an active admin is logged in
        $hasValidSignature = $request->hasValidSignature();
        $user = $request->user();
        $isAdmin = $user && $user->isAdmin() && $user->is_active;

        if (! $hasValidSignature && ! $isAdmin) {
            abort(403, 'Invalid or expired signature.');
        }

        $booking = Booking::where('reference', $reference)->firstOrFail();
        $path = 'invoices/' . $booking->reference . '.pdf';

        // Always regenerate to ensure latest business settings are reflected
        $invoiceService->generate($booking);

        return Storage::disk('public')->download($path, 'Invoice-' . $booking->reference . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
