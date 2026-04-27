<?php

declare(strict_types=1);

namespace App\Livewire\Admin\ContactMessages;

use App\Models\ContactMessage;
use App\Traits\HasAdminAuthorization;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Contact Messages')]
class Index extends Component
{
    use HasAdminAuthorization;
    use WithPagination;

    public string $search = '';

    #[Url]
    public string $filterStatus = '';

    #[Url]
    public string $startDate = '';

    #[Url]
    public string $endDate = '';

    public ?ContactMessage $viewing = null;

    public string $responseNotes = '';

    public ?int $confirmingDeleteId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->authorizePermission('manage_contact_messages');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingStartDate(): void
    {
        $this->resetPage();
    }

    public function updatingEndDate(): void
    {
        $this->resetPage();
    }

    public function filterToday(): void
    {
        $this->startDate = now()->toDateString();
        $this->endDate = now()->toDateString();
        $this->resetPage();
    }

    public function filterThisWeek(): void
    {
        $this->startDate = now()->startOfWeek()->toDateString();
        $this->endDate = now()->endOfWeek()->toDateString();
        $this->resetPage();
    }

    public function filterThisMonth(): void
    {
        $this->startDate = now()->startOfMonth()->toDateString();
        $this->endDate = now()->endOfMonth()->toDateString();
        $this->resetPage();
    }

    public function clearDateFilter(): void
    {
        $this->startDate = '';
        $this->endDate = '';
        $this->resetPage();
    }

    public function openMessage(int $id): void
    {
        $message = ContactMessage::with('respondedBy')->findOrFail($id);

        if ($message->status === 'new') {
            $message->update(['status' => 'read']);
        }

        $this->viewing = $message->fresh('respondedBy');
        $this->responseNotes = '';
    }

    public function closeMessage(): void
    {
        $this->viewing = null;
        $this->responseNotes = '';
    }

    public function markResponded(): void
    {
        if (! $this->viewing) {
            return;
        }

        $this->viewing->update([
            'status' => 'responded',
            'response_notes' => $this->responseNotes ?: null,
            'responded_at' => now(),
            'responded_by_id' => Auth::id(),
        ]);

        $this->viewing = $this->viewing->fresh('respondedBy');
        $this->responseNotes = '';

        session()->flash('success', 'Message marked as responded.');
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function deleteMessage(int $id): void
    {
        ContactMessage::findOrFail($id)->delete();

        if ($this->viewing?->id === $id) {
            $this->viewing = null;
        }

        $this->confirmingDeleteId = null;
        session()->flash('success', 'Contact message deleted.');
    }

    public function render(): View
    {
        $messages = ContactMessage::query()
            ->when($this->search, fn ($q) => $q->where(function ($q): void {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%")
                    ->orWhere('inquiry_type', 'like', "%{$this->search}%")
                    ->orWhere('message', 'like', "%{$this->search}%");
            }))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->startDate, fn ($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->latest()
            ->paginate(25);

        $stats = [
            'total' => ContactMessage::count(),
            'new' => ContactMessage::where('status', 'new')->count(),
            'responded' => ContactMessage::where('status', 'responded')->count(),
            'today' => ContactMessage::whereDate('created_at', today())->count(),
        ];

        return view('livewire.admin.contact-messages.index', compact('messages', 'stats'));
    }
}
