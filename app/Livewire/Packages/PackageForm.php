<?php

declare(strict_types=1);

namespace App\Livewire\Packages;

use App\Jobs\OptimiseImage;
use App\Models\Category;
use App\Models\Package;
use App\Traits\HasAdminAuthorization;
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
    use HasAdminAuthorization;
    use WithFileUploads;

    public ?Package $package = null;

    public string $name = '';

    public string $description = '';

    public string $price = '';

    public ?int $category_id = null;

    public bool $is_active = true;

    public array $features = [];

    public bool $is_popular = false;

    public bool $window_exempt = false;

    public $image;

    public ?string $existing_image = null;

    public bool $showDeleteModal = false;

    public function mount(?Package $package = null): void
    {
        $this->authorizePermission('manage_packages');
        if ($package && $package->exists) {
            $this->package = $package;
            $this->name = $this->package->name;
            $this->description = $this->package->description ?? '';
            $this->price = (string) $this->package->price;
            $this->features = $this->package->features ?? [];
            $this->is_popular = (bool) $this->package->is_popular;
            $this->window_exempt = (bool) $this->package->window_exempt;
            $this->category_id = $this->package->category_id;
            $this->is_active = $this->package->is_active;
            $this->existing_image = $this->package->image_path;
        }
    }

    public function addFeature(): void
    {
        $this->features[] = '';
    }

    public function removeFeature(int $index): void
    {
        unset($this->features[$index]);
        $this->features = array_values($this->features);
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'string', 'max:150'],
            'is_popular' => ['boolean'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'is_active' => ['boolean'],
            'window_exempt' => ['boolean'],
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

        $data = [
            'name' => $this->name,
            'slug' => $slug,
            'description' => $this->description,
            'price' => $this->price,
            'features' => array_filter($this->features),
            'is_popular' => $this->is_popular,
            'window_exempt' => $this->window_exempt,
            'category_id' => $this->category_id,
            'is_active' => $this->is_active,
            'image_path' => $imagePath,
        ];

        if ($this->package) {
            $this->package->update($data);
            $savedPackage = $this->package;
            session()->flash('success', 'Package updated successfully.');
        } else {
            $data['sort_order'] = Package::max('sort_order') + 1;
            $savedPackage = Package::create($data);
            session()->flash('success', 'Package created successfully.');
        }

        // I dispatch the optimisation job only when a new image was uploaded
        if ($this->image && $imagePath) {
            OptimiseImage::dispatch(
                disk: 'public',
                path: (string) $imagePath,
                modelClass: Package::class,
                modelId: $savedPackage->id,
                modelColumn: 'image_path',
            );
        }

        return $this->redirect(route('admin.manage-packages.index'));
    }

    public function deletePackage()
    {
        if (! $this->package) {
            return;
        }

        if ($this->package->bookingItems()->count() > 0) {
            $this->showDeleteModal = false;
            $this->addError('delete', "Cannot delete '{$this->package->name}' because it has existing bookings.");

            return;
        }

        $this->package->delete();
        session()->flash('success', 'Package deleted successfully.');

        return $this->redirect(route('admin.manage-packages.index'));
    }

    public function render(): View
    {
        return view('livewire.packages.package-form', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }
}
