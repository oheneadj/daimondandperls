<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Bookings;

use App\Enums\BookingStatus;
use App\Enums\BookingType;
use App\Models\Booking;
use App\Notifications\BookingCompletedNotification;
use App\Notifications\BookingConfirmedNotification;
use App\Notifications\QuoteUpdatedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Booking Details')]
class Show extends Component
{
    public Booking $booking;

    public string $cancelReason = '';

    public string $actionToConfirm = '';

    public string $verificationNotes = '';

    public bool $showCancelModal = false;

    public bool $showActionModal = false;

    public bool $showVerifyModal = false;

    public ?string $quoteAmount = null;

    public bool $showQuoteModal = false;

    // Event details editing
    public bool $showEventEditModal = false;

    public ?string $editEventDate = null;

    public ?string $editEventStartTime = null;

    public ?string $editEventEndTime = null;

    public ?string $editEventType = null;

    public ?int $editPax = null;

    public bool $editIsBuffet = false;

    public ?string $editQuoteAmount = null;

    public string $confirmPassword = '';

    public function mount(Booking $booking)
    {
        $this->booking = $booking->load(['customer', 'items.package', 'payment.verifiedBy']);
    }

    public function getCanBeConfirmedProperty(): bool
    {
        return $this->booking->status === BookingStatus::Pending;
    }

    public function getCanBeVerifiedProperty(): bool
    {
        return $this->booking->status === BookingStatus::Confirmed
            && $this->booking->payment_status !== \App\Enums\PaymentStatus::Paid;
    }

    public function getCanBePreparedProperty(): bool
    {
        return $this->booking->status === BookingStatus::Confirmed
            && $this->booking->payment_status === \App\Enums\PaymentStatus::Paid;
    }

    public function getCanBeDispatchedProperty(): bool
    {
        return $this->booking->status === BookingStatus::InPreparation;
    }

    public function getCanBeCompletedProperty(): bool
    {
        return $this->booking->status === BookingStatus::ReadyForDelivery;
    }

    public function getCanBeCancelledProperty(): bool
    {
        return ! in_array($this->booking->status, [BookingStatus::Completed, BookingStatus::Cancelled]);
    }

    public function confirmBooking()
    {
        if ($this->canBeConfirmed) {
            $this->booking->update([
                'status' => BookingStatus::Confirmed,
                'confirmed_by' => Auth::id(),
                'confirmed_at' => now(),
            ]);

            // Refetch the relationships that might have been impacted
            $this->booking->refresh();

            $this->dispatch('banner', ['style' => 'success', 'message' => 'Booking confirmed successfully.']);
        }
    }

    public function startPreparation()
    {
        if ($this->canBePrepared) {
            $this->booking->update([
                'status' => BookingStatus::InPreparation,
            ]);
            $this->booking->refresh();
            $this->dispatch('banner', ['style' => 'success', 'message' => 'Booking preparation started successfully.']);
        }
    }

    public function markAsReadyForDelivery()
    {
        if ($this->canBeDispatched) {
            $this->booking->update([
                'status' => BookingStatus::ReadyForDelivery,
            ]);
            $this->booking->refresh();
            $this->dispatch('banner', ['style' => 'success', 'message' => 'Booking marked as ready for delivery.']);
        }
    }

    public function completeBooking()
    {
        if ($this->canBeCompleted) {
            $this->booking->update([
                'status' => BookingStatus::Completed,
                'completed_at' => now(),
            ]);

            // Notify customer and database
            if ($this->booking->customer) {
                $this->booking->customer->notify(new BookingCompletedNotification($this->booking));
            }

            $this->booking->refresh();

            $this->dispatch('banner', ['style' => 'success', 'message' => 'Booking completed successfully.']);
        }
    }

    public function openCancelModal()
    {
        $this->cancelReason = '';
        $this->showCancelModal = true;
    }

    public function closeCancelModal()
    {
        $this->showCancelModal = false;
    }

    public function cancelBooking()
    {
        if ($this->canBeCancelled) {
            $this->booking->update([
                'status' => BookingStatus::Cancelled,
                'cancelled_at' => now(),
                'cancelled_reason' => $this->cancelReason,
            ]);
            $this->booking->refresh();
            $this->closeCancelModal();

            $this->redirectRoute('admin.bookings.show', ['booking' => $this->booking], navigate: true);

            $this->dispatch('banner', ['style' => 'success', 'message' => 'Booking cancelled successfully.']);
        }
    }

    public function promptAction(string $action)
    {
        $this->actionToConfirm = $action;
        $this->showActionModal = true;
    }

    public function closeActionModal()
    {
        $this->actionToConfirm = '';
        $this->showActionModal = false;
    }

    public function openVerifyModal()
    {
        $this->verificationNotes = '';
        $this->showVerifyModal = true;
    }

    public function closeVerifyModal()
    {
        $this->showVerifyModal = false;
    }

    public function getCanSetQuoteProperty(): bool
    {
        return $this->booking->booking_type === BookingType::Event
            && in_array($this->booking->status, [BookingStatus::Pending, BookingStatus::Confirmed])
            && $this->booking->payment_status !== \App\Enums\PaymentStatus::Paid;
    }

    public function openQuoteModal(): void
    {
        $this->quoteAmount = $this->booking->total_amount > 0
            ? (string) $this->booking->total_amount
            : null;
        $this->showQuoteModal = true;
    }

    public function closeQuoteModal(): void
    {
        $this->quoteAmount = null;
        $this->showQuoteModal = false;
    }

    public function updateQuote(): void
    {
        $this->validate([
            'quoteAmount' => ['required', 'numeric', 'min:0.01'],
        ], [
            'quoteAmount.required' => 'Please enter a quote amount.',
            'quoteAmount.min' => 'The quote amount must be greater than zero.',
        ]);

        $this->booking->update([
            'total_amount' => (float) $this->quoteAmount,
        ]);

        if ($this->booking->customer) {
            $this->booking->customer->notify(new QuoteUpdatedNotification($this->booking));
        }

        $this->booking->refresh();
        $this->closeQuoteModal();

        $this->dispatch('banner', ['style' => 'success', 'message' => 'Quote of GH₵ '.number_format((float) $this->quoteAmount, 2).' sent to customer.']);
    }

    public function getCanEditEventProperty(): bool
    {
        return $this->booking->booking_type === BookingType::Event
            && in_array($this->booking->status, [BookingStatus::Pending, BookingStatus::Confirmed])
            && $this->booking->payment_status !== \App\Enums\PaymentStatus::Paid;
    }

    public function openEventEditModal(): void
    {
        $this->editEventDate = $this->booking->event_date?->format('Y-m-d');
        $this->editEventStartTime = $this->booking->event_start_time;
        $this->editEventEndTime = $this->booking->event_end_time;
        $this->editEventType = $this->booking->event_type?->value;
        $this->editPax = $this->booking->pax;
        $this->editIsBuffet = (bool) $this->booking->is_buffet;
        $this->editQuoteAmount = $this->booking->total_amount > 0
            ? (string) $this->booking->total_amount
            : null;
        $this->confirmPassword = '';
        $this->showEventEditModal = true;
    }

    public function closeEventEditModal(): void
    {
        $this->showEventEditModal = false;
        $this->confirmPassword = '';
        $this->resetErrorBag();
    }

    public function updateEventDetails(): void
    {
        $this->validate([
            'editQuoteAmount' => ['required', 'numeric', 'min:0.01'],
            'editEventDate' => ['nullable', 'date'],
            'editEventStartTime' => ['nullable', 'string'],
            'editEventEndTime' => ['nullable', 'string'],
            'editEventType' => ['nullable', 'string'],
            'editPax' => ['nullable', 'integer', 'min:1'],
            'confirmPassword' => ['required', 'string'],
        ], [
            'editQuoteAmount.required' => 'Please enter a quote amount.',
            'editQuoteAmount.min' => 'The quote amount must be greater than zero.',
            'confirmPassword.required' => 'Please enter your password to confirm.',
        ]);

        if (! Hash::check($this->confirmPassword, Auth::user()->password)) {
            $this->addError('confirmPassword', 'The password is incorrect.');

            return;
        }

        $this->booking->update([
            'total_amount' => (float) $this->editQuoteAmount,
            'event_date' => $this->editEventDate,
            'event_start_time' => $this->editEventStartTime,
            'event_end_time' => $this->editEventEndTime,
            'event_type' => $this->editEventType,
            'pax' => $this->editPax,
            'is_buffet' => $this->editIsBuffet,
        ]);

        if ($this->booking->customer) {
            $this->booking->customer->notify(new QuoteUpdatedNotification($this->booking));
        }

        $this->booking->refresh();
        $this->closeEventEditModal();

        $this->dispatch('banner', ['style' => 'success', 'message' => 'Event details updated and quote of GH₵ '.number_format((float) $this->editQuoteAmount, 2).' sent to customer.']);
    }

    public function verifyPayment()
    {
        if ($this->canBeVerified) {
            $this->booking->update([
                'payment_status' => \App\Enums\PaymentStatus::Paid,
            ]);

            // Create or update payment record
            $this->booking->payment()->updateOrCreate(
                ['booking_id' => $this->booking->id],
                [
                    'amount' => $this->booking->total_amount,
                    'currency' => 'GHS',
                    'status' => \App\Enums\PaymentGatewayStatus::Successful,
                    'method' => \App\Enums\PaymentMethod::BankTransfer, // Default for manual
                    'gateway' => \App\Enums\PaymentGateway::Manual,
                    'paid_at' => now(),
                    'verified_by' => Auth::id(),
                    'verified_at' => now(),
                    'gateway_response' => ['notes' => $this->verificationNotes],
                ]
            );

            $this->booking->refresh();

            // Send confirmation notification with invoice
            if ($this->booking->customer) {
                $this->booking->customer->notify(new BookingConfirmedNotification(
                    $this->booking,
                    app(\App\Services\InvoiceService::class)->getDownloadUrl($this->booking)
                ));
            }

            $this->closeVerifyModal();
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Payment verified successfully. Implementation unlocked.']);

            $this->redirectRoute('admin.bookings.show', ['booking' => $this->booking], navigate: true);
        }
    }

    public function executeAction()
    {
        if ($this->actionToConfirm === 'confirmBooking') {
            $this->confirmBooking();
        } elseif ($this->actionToConfirm === 'startPreparation') {
            $this->startPreparation();
        } elseif ($this->actionToConfirm === 'readyForDelivery') {
            $this->markAsReadyForDelivery();
        } elseif ($this->actionToConfirm === 'completeBooking') {
            $this->completeBooking();
        }

        $this->closeActionModal();

        $this->redirectRoute('admin.bookings.show', ['booking' => $this->booking], navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.bookings.show');
    }
}
