<?php

declare(strict_types=1);

namespace App\Livewire\Categories;

use App\Models\Category;
use App\Models\Package;
use App\Traits\HasAdminAuthorization;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Categories')]
#[Layout('layouts.admin')]
class CategoryIndex extends Component
{
    use HasAdminAuthorization;
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    public bool $showFormModal = false;

    public string $name = '';

    public ?int $editingCategoryId = null;

    public bool $booking_window_enabled = false;

    public ?int $delivery_day = null;

    public ?int $cutoff_day = null;

    public string $cutoff_time = '';

    protected $rules = [
        'name' => 'required|min:2|max:255',
        'booking_window_enabled' => 'boolean',
        'delivery_day' => 'nullable|required_if:booking_window_enabled,true|integer|between:1,7',
        'cutoff_day' => 'nullable|required_if:booking_window_enabled,true|integer|between:1,7',
        'cutoff_time' => 'nullable|required_if:booking_window_enabled,true|date_format:H:i',
    ];

    protected $messages = [
        'delivery_day.required_if' => 'Delivery day is required when booking window is enabled.',
        'cutoff_day.required_if' => 'Cutoff day is required when booking window is enabled.',
        'cutoff_time.required_if' => 'Cutoff time is required when booking window is enabled.',
        'cutoff_time.date_format' => 'Cutoff time must be in HH:MM format.',
    ];

    #[Url(history: true)]
    public string $sortField = 'name';

    #[Url(history: true)]
    public string $sortDirection = 'asc';

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public bool $showDeleteModal = false;

    public ?int $categoryToDeleteId = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetErrorBag();
        $this->reset(['name', 'editingCategoryId', 'booking_window_enabled', 'delivery_day', 'cutoff_day', 'cutoff_time']);
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->resetErrorBag();
        $category = Category::findOrFail($id);
        $this->editingCategoryId = $category->id;
        $this->name = $category->name;
        $this->booking_window_enabled = (bool) $category->booking_window_enabled;
        $this->delivery_day = $category->delivery_day;
        $this->cutoff_day = $category->cutoff_day;
        $this->cutoff_time = $category->cutoff_time ? substr($category->cutoff_time, 0, 5) : '';
        $this->showFormModal = true;
    }

    public function saveCategory(): void
    {
        $this->validate();

        $slug = \Illuminate\Support\Str::slug($this->name);

        // Ensure unique slug
        $query = Category::where('slug', $slug);
        if ($this->editingCategoryId) {
            $query->where('id', '!=', $this->editingCategoryId);
        }

        if ($query->exists()) {
            $this->addError('name', 'This collection name is already taken.');

            return;
        }

        $windowData = [
            'booking_window_enabled' => $this->booking_window_enabled,
            'delivery_day' => $this->booking_window_enabled ? $this->delivery_day : null,
            'cutoff_day' => $this->booking_window_enabled ? $this->cutoff_day : null,
            'cutoff_time' => $this->booking_window_enabled && $this->cutoff_time ? $this->cutoff_time.':00' : null,
        ];

        if ($this->editingCategoryId) {
            $category = Category::findOrFail($this->editingCategoryId);
            $category->update(array_merge(['name' => $this->name, 'slug' => $slug], $windowData));
            $message = 'Collection updated successfully';
        } else {
            Category::create(array_merge(['name' => $this->name, 'slug' => $slug], $windowData));
            $message = 'Collection created successfully';
        }

        $this->showFormModal = false;
        $this->dispatch('banner', style: 'success', message: $message);
    }

    public function confirmDelete(int $id): void
    {
        $this->categoryToDeleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteCategory(): void
    {
        if (! $this->categoryToDeleteId) {
            return;
        }

        $category = Category::findOrFail($this->categoryToDeleteId);

        if ($category->packages()->count() > 0) {
            $this->dispatch('banner', style: 'error', message: "Cannot delete '{$category->name}' because it has packages inside.");
            $this->showDeleteModal = false;
            $this->categoryToDeleteId = null;

            return;
        }

        $category->delete();
        $this->showDeleteModal = false;
        $this->categoryToDeleteId = null;
        $this->dispatch('banner', style: 'success', message: 'Collection deleted successfully.');
    }

    protected function getStats(): array
    {
        return [
            'total' => Category::count(),
            'packages' => Package::count(),
            'empty' => Category::doesntHave('packages')->count(),
            'most_popular' => Category::withCount('packages')->orderByDesc('packages_count')->first()?->name ?? 'None',
        ];
    }

    public function mount(): void
    {
        $this->authorizePermission('manage_categories');
    }

    public function render(): View
    {
        return view('livewire.categories.category-index', [
            'categories' => $this->getCategories(),
            'stats' => $this->getStats(),
        ]);
    }

    protected function getCategories(): LengthAwarePaginator
    {
        return Category::query()
            ->withCount('packages')
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }
}
