<?php

declare(strict_types=1);

namespace App\Livewire\Packages;

use App\Models\Category;
use App\Models\Package;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Package Form')]
#[Layout('layouts.admin')]
class PackageForm extends Component
{
    use WithFileUploads;

    public ?Package $package = null;

    public string $name = '';

    public string $description = '';

    public string $price = '';

    public string $serving_size = '';

    public ?int $category_id = null;

    public bool $is_active = true;

    public $image;

    public ?string $existing_image = null;

    public function mount(?Package $package = null): void
    {
        if ($package && $package->exists) {
            $this->package = $package;
            $this->name = $this->package->name;
            $this->description = $this->package->description ?? '';
            $this->price = (string) $this->package->price;
            $this->serving_size = $this->package->serving_size ?? '';
            $this->category_id = $this->package->category_id;
            $this->is_active = $this->package->is_active;
            $this->existing_image = $this->package->image_path;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'serving_size' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'is_active' => ['boolean'],
            'image' => ['nullable', 'image', 'max:2048'], // 2MB Max
        ]);

        $slug = Str::slug($this->name);

        // Ensure unique slug
        $query = Package::where('slug', $slug);
        if ($this->package) {
            $query->where('id', '!=', $this->package->id);
        }

        if ($query->exists()) {
            $this->addError('name', 'A package with a similar name already exists.');

            return;
        }

        $imagePath = $this->existing_image;

        if ($this->image) {
            $imagePath = $this->image->store('packages', 'public');
        }

        if ($this->package) {
            $this->package->update([
                'name' => $this->name,
                'slug' => $slug,
                'description' => $this->description,
                'price' => $this->price,
                'serving_size' => $this->serving_size,
                'category_id' => $this->category_id,
                'is_active' => $this->is_active,
                'image_path' => $imagePath,
            ]);
            $this->dispatch('banner', style: 'success', message: 'Package updated successfully.');
        } else {
            Package::create([
                'name' => $this->name,
                'slug' => $slug,
                'description' => $this->description,
                'price' => $this->price,
                'serving_size' => $this->serving_size,
                'category_id' => $this->category_id,
                'is_active' => $this->is_active,
                'image_path' => $imagePath,
                'sort_order' => Package::max('sort_order') + 1,
            ]);
            $this->dispatch('banner', style: 'success', message: 'Package created successfully.');
        }

        return $this->redirect(route('admin.manage-packages.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.packages.package-form', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}
