<?php

declare(strict_types=1);

namespace App\Livewire\Categories;

use App\Models\Category;
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

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function deleteCategory(int $id): void
    {
        $category = Category::findOrFail($id);

        // Prevent deleting if packages are attached to this category
        if ($category->packages()->count() > 0) {
            $this->addError('error', "Cannot delete category '{$category->name}' because it contains packages.");

            return;
        }

        $category->delete();
        $this->dispatch('banner', style: 'success', message: 'Category deleted successfully.');
    }

    public function render(): View
    {
        return view('livewire.categories.category-index', [
            'categories' => $this->getCategories(),
        ]);
    }

    protected function getCategories(): LengthAwarePaginator
    {
        return Category::query()
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);
    }
}
