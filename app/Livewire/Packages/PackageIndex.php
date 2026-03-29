<?php

declare(strict_types=1);

namespace App\Livewire\Packages;

use App\Models\Category;
use App\Models\Package;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Packages')]
#[Layout('layouts.admin')]
class PackageIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public ?int $categoryId = null;

    #[Url(history: true)]
    public string $status = 'all'; // all, active, inactive

    public string $sortField = 'sort_order';

    public string $sortDirection = 'asc';

    public bool $showDeleteModal = false;

    public ?int $packageToDeleteId = null;

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function toggleActive(int $id): void
    {
        $package = Package::findOrFail($id);
        $package->update(['is_active' => ! $package->is_active]);

        $status = $package->is_active ? 'activated' : 'deactivated';
        $this->dispatch('banner', style: 'success', message: "Package '{$package->name}' {$status} successfully.");
    }

    public function confirmDelete(int $id): void
    {
        $this->packageToDeleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deletePackage(): void
    {
        if (! $this->packageToDeleteId) {
            return;
        }

        $package = Package::findOrFail($this->packageToDeleteId);

        // Prevent deleting if bookings are attached
        if ($package->bookingItems()->count() > 0) {
            $this->addError('error', "Cannot delete package '{$package->name}' because it has existing bookings.");
            $this->showDeleteModal = false;
            $this->packageToDeleteId = null;

            return;
        }

        $package->delete();
        $this->showDeleteModal = false;
        $this->packageToDeleteId = null;
        $this->dispatch('banner', style: 'success', message: 'Package deleted successfully.');
    }

    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            Package::where('id', $id)->update(['sort_order' => $index]);
        }
        $this->dispatch('banner', style: 'success', message: 'Packages reordered successfully.');
    }

    public function render(): View
    {
        return view('livewire.packages.package-index', [
            'packages' => $this->getPackages(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    protected function getPackages(): LengthAwarePaginator
    {
        return Package::query()
            ->with('category')
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($this->categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($this->status !== 'all', function ($query) {
                $query->where('is_active', $this->status === 'active');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(12);
    }
}
