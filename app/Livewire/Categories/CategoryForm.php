<?php

declare(strict_types=1);

namespace App\Livewire\Categories;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.admin')]
class CategoryForm extends Component
{
    public ?Category $category = null;

    #[Rule('required|min:2|max:255', as: 'category name')]
    public string $name = '';

    public function mount(?Category $category = null): void
    {
        if ($category && $category->exists) {
            $this->category = $category;
            $this->name = $category->name;
        }
    }

    public function save()
    {
        $this->validate();

        $slug = Str::slug($this->name);

        // Ensure slug uniqueness
        $query = Category::where('slug', $slug);
        if ($this->category) {
            $query->where('id', '!=', $this->category->id);
        }

        if ($query->exists()) {
            $this->addError('name', 'A category with a similar name already exists.');

            return;
        }

        if ($this->category) {
            $this->category->update([
                'name' => $this->name,
                'slug' => $slug,
            ]);
            $message = 'Category updated successfully.';
        } else {
            Category::create([
                'name' => $this->name,
                'slug' => $slug,
            ]);
            $message = 'Category created successfully.';
        }

        $this->dispatch('banner', style: 'success', message: $message);

        return $this->redirect(route('admin.categories.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.categories.category-form');
    }
}
