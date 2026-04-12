<?php

declare(strict_types=1);

namespace App\Livewire\Admin\ErrorLogs;

use App\Models\ActivityLog;
use App\Models\BookingNotificationLog;
use App\Models\ErrorLog;
use App\Models\SmsLog;
use App\Models\User;
use App\Traits\HasAdminAuthorization;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('System Logs')]
class ErrorLogIndex extends Component
{
    use HasAdminAuthorization;
    use WithPagination;

    #[Url]
    public string $activeTab = 'errors';

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $filterLevel = '';

    #[Url]
    public string $filterSource = '';

    #[Url]
    public string $filterResolved = '';

    public ?ErrorLog $viewing = null;

    public string $resolutionNote = '';

    public function updatedActiveTab(): void
    {
        $this->resetPage();
        $this->search = '';
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterLevel(): void
    {
        $this->resetPage();
    }

    public function updatedFilterSource(): void
    {
        $this->resetPage();
    }

    public function updatedFilterResolved(): void
    {
        $this->resetPage();
    }

    public function viewLog(int $id): void
    {
        $this->viewing = ErrorLog::with('resolvedBy')->find($id);
        $this->resolutionNote = $this->viewing?->resolution_note ?? '';
    }

    public function closeLog(): void
    {
        $this->viewing = null;
        $this->resolutionNote = '';
    }

    public function markResolved(int $id): void
    {
        /** @var User $user */
        $user = Auth::user();

        $log = ErrorLog::findOrFail($id);
        $log->update([
            'resolved' => true,
            'resolution_note' => trim($this->resolutionNote) ?: null,
            'resolved_by' => $user->id,
            'resolved_at' => now(),
        ]);

        $this->viewing = $log->fresh('resolvedBy');
        $this->dispatch('toast', type: 'success', message: 'Error marked as resolved.');
    }

    public function markUnresolved(int $id): void
    {
        $log = ErrorLog::findOrFail($id);
        $log->update([
            'resolved' => false,
            'resolution_note' => null,
            'resolved_by' => null,
            'resolved_at' => null,
        ]);

        $this->viewing = $log->fresh('resolvedBy');
        $this->dispatch('toast', type: 'success', message: 'Error marked as unresolved.');
    }

    public function mount(): void
    {
        $this->authorizePermission('view_error_logs');
    }

    public function render(): View
    {
        $logs = null;
        $smsLogs = null;
        $activityLogs = null;
        $notificationLogs = null;

        if ($this->activeTab === 'errors') {
            $logs = ErrorLog::query()
                ->with('resolvedBy')
                ->when($this->search, fn ($q) => $q->where(function ($q) {
                    $q->where('booking_reference', 'like', "%{$this->search}%")
                        ->orWhere('message', 'like', "%{$this->search}%")
                        ->orWhere('error_code', 'like', "%{$this->search}%")
                        ->orWhere('payer_number', 'like', "%{$this->search}%");
                }))
                ->when($this->filterLevel, fn ($q) => $q->where('level', $this->filterLevel))
                ->when($this->filterSource, fn ($q) => $q->where('source', $this->filterSource))
                ->when($this->filterResolved !== '', fn ($q) => $q->where('resolved', (bool) $this->filterResolved))
                ->latest()
                ->paginate(25);
        } elseif ($this->activeTab === 'sms') {
            $smsLogs = SmsLog::query()
                ->when($this->search, fn ($q) => $q->where(function ($q) {
                    $q->where('to', 'like', "%{$this->search}%")
                        ->orWhere('message', 'like', "%{$this->search}%")
                        ->orWhere('message_id', 'like', "%{$this->search}%");
                }))
                ->latest()
                ->paginate(25);
        } elseif ($this->activeTab === 'activity') {
            $activityLogs = ActivityLog::query()
                ->with('user')
                ->when($this->search, fn ($q) => $q->where(function ($q) {
                    $q->where('action', 'like', "%{$this->search}%")
                        ->orWhere('subject_type', 'like', "%{$this->search}%")
                        ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$this->search}%"));
                }))
                ->latest('created_at')
                ->paginate(25);
        } elseif ($this->activeTab === 'notifications') {
            $notificationLogs = BookingNotificationLog::query()
                ->with('booking')
                ->when($this->search, fn ($q) => $q->where(function ($q) {
                    $q->where('recipient', 'like', "%{$this->search}%")
                        ->orWhere('template', 'like', "%{$this->search}%")
                        ->orWhereHas('booking', fn ($bq) => $bq->where('reference', 'like', "%{$this->search}%"));
                }))
                ->latest()
                ->paginate(25);
        }

        $stats = [
            'errors_unresolved' => ErrorLog::where('resolved', false)->count(),
            'sms_total' => SmsLog::count(),
            'activity_today' => ActivityLog::whereDate('created_at', today())->count(),
            'notifications_failed' => BookingNotificationLog::whereNotNull('error_message')->count(),
        ];

        return view('livewire.admin.error-logs.error-log-index', [
            'logs' => $logs,
            'smsLogs' => $smsLogs,
            'activityLogs' => $activityLogs,
            'notificationLogs' => $notificationLogs,
            'stats' => $stats,
        ]);
    }
}
