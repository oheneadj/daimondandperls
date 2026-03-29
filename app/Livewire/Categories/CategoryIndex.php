<?php

declare(strict_types=1);

namespace App\Livewire\Categories;

use App\Models\Category;
use App\Models\Package;
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
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    public bool $showFormModal = false;

    public string $name = '';

    public ?int $editingCategoryId = null;

    protected $rules = [
        'name' => 'required|min:2|max:255',
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
        $this->reset(['name', 'editingCategoryId']);
        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->resetErrorBag();
        $category = Category::findOrFail($id);
        $this->editingCategoryId = $category->id;
        $this->name = $category->name;
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

        if ($this->editingCategoryId) {
            $category = Category::findOrFail($this->editingCategoryId);
            $category->update([
                'name' => $this->name,
                'slug' => $slug,
            ]);
            $message = 'Collection updated successfully';
        } else {
            Category::create([
                'name' => $this->name,
                'slug' => $slug,
            ]);
            $message = 'Collection created successfully';
        }

        $this->showFormModal = false;
        $this->dispatch('banner', style: 'success', message: 'Package deleted successfully.');
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
