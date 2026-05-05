<?php

declare(strict_types=1);

namespace App\Livewire\Admin\BookingWindows;

use App\Models\BookingWindow;
use App\Services\BookingWindowService;
use App\Traits\HasAdminAuthorization;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Booking Windows')]
#[Layout('layouts.admin')]
class BookingWindowIndex extends Component
{
    use HasAdminAuthorization;
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    public bool $showFormModal = false;

    public bool $showDeleteModal = false;

    public ?int $editingWindowId = null;

    public ?int $windowToDeleteId = null;

    // Form fields
    public string $name = '';

    public ?int $delivery_day = null;

    public ?int $cutoff_day = null;

    public string $cutoff_time = '';

    public function mount(): void
    {
        $this->authorizePermission('manage_packages');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function previewLabel(): string
    {
        if (! $this->delivery_day || ! $this->cutoff_day || ! $this->cutoff_time) {
            return '';
        }

        $deliveryLabel = BookingWindowService::DAY_LABELS[$this->delivery_day] ?? '';
        $cutoffLabel = BookingWindowService::DAY_LABELS[$this->cutoff_day] ?? '';
        $time = date('g:ia', strtotime($this->cutoff_time));

        return "Customers can order until {$cutoffLabel} at {$time} for {$deliveryLabel} delivery.";
    }

    public function openCreateModal(): void
    {
        $this->resetErrorBag();
        $this->reset(['name', 'delivery_day', 'cutoff_day', 'cutoff_time', 'editingWindowId']);
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->resetErrorBag();
        $window = BookingWindow::findOrFail($id);
        $this->editingWindowId = $window->id;
        $this->name = $window->name;
        $this->delivery_day = $window->delivery_day;
        $this->cutoff_day = $window->cutoff_day;
        $this->cutoff_time = substr($window->cutoff_time, 0, 5);
        $this->showFormModal = true;
    }

    public function saveWindow(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'delivery_day' => ['required', 'integer', 'between:1,7'],
            'cutoff_day' => ['required', 'integer', 'between:1,7'],
            'cutoff_time' => ['required', 'date_format:H:i'],
        ], [
            'cutoff_time.date_format' => 'Cutoff time must be in HH:MM format.',
        ]);

        $data = [
            'name' => $this->name,
            'delivery_day' => $this->delivery_day,
            'cutoff_day' => $this->cutoff_day,
            'cutoff_time' => $this->cutoff_time.':00',
        ];

        if ($this->editingWindowId) {
            BookingWindow::findOrFail($this->editingWindowId)->update($data);
            $message = 'Booking window updated successfully.';
        } else {
            BookingWindow::create($data);
            $message = 'Booking window created successfully.';
        }

        $this->showFormModal = false;
        $this->dispatch('banner', style: 'success', message: $message);
    }

    public function confirmDelete(int $id): void
    {
        $this->windowToDeleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteWindow(): void
    {
        if (! $this->windowToDeleteId) {
            return;
        }

        $window = BookingWindow::findOrFail($this->windowToDeleteId);
        $window->packages()->detach();
        $window->delete();

        $this->showDeleteModal = false;
        $this->windowToDeleteId = null;
        $this->dispatch('banner', style: 'success', message: 'Booking window deleted.');
    }

    public function render(): View
    {
        $windows = BookingWindow::query()
            ->withCount('packages')
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.booking-windows.booking-window-index', [
            'windows' => $windows,
            'dayLabels' => BookingWindowService::DAY_LABELS,
        ]);
    }
}
