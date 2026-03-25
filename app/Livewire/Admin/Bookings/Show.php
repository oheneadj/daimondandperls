<?php

namespace App\Livewire\Admin\Bookings;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Notifications\BookingCompletedNotification;
use App\Notifications\BookingConfirmedNotification;
use Illuminate\Support\Facades\Auth;
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

    public function getCanBeCompletedProperty(): bool
    {
        return $this->booking->status === BookingStatus::InPreparation;
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
        }
    }

    public function startPreparation()
    {
        if ($this->canBePrepared) {
            $this->booking->update([
                'status' => BookingStatus::InPreparation,
            ]);
            $this->booking->refresh();
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
