<?php

use App\Livewire\Packages\PackageForm;
use App\Models\Category;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the create package page', function () {
    $this->actingAs($this->user)
        ->get(route('admin.manage-packages.create'))
        ->assertOk()
        ->assertSee('Add New Package');
});

it('validates package form submission', function () {
    Livewire::actingAs($this->user)
        ->test(PackageForm::class)
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['name' => 'required', 'price' => 'required']);
});

it('can create a new package', function () {
    $category = Category::factory()->create();

    Livewire::actingAs($this->user)
        ->test(PackageForm::class)
        ->set('name', 'Gold Package')
        ->set('price', 1500.00)
        ->set('category_id', $category->id)
        ->call('save')
        ->assertRedirect(route('admin.manage-packages.index'));

    $this->assertDatabaseHas('packages', [
        'name' => 'Gold Package',
        'price' => 1500.00,
        'category_id' => $category->id,
        'slug' => 'gold-package',
    ]);
});

it('can upload an image during creation', function () {
    Storage::fake('public');
    $file = UploadedFile::fake()->image('package.jpg');

    Livewire::actingAs($this->user)
        ->test(PackageForm::class)
        ->set('name', 'Silver Package')
        ->set('price', 1000.00)
        ->set('image', $file)
        ->call('save');

    $package = Package::where('name', 'Silver Package')->first();
    expect($package->image_path)->not->toBeNull();
    Storage::disk('public')->assertExists($package->image_path);
});

it('prevents duplicate package names similar to existing ones', function () {
    Package::factory()->create(['name' => 'Platinum Package', 'slug' => 'platinum-package']);

    Livewire::actingAs($this->user)
        ->test(PackageForm::class)
        ->set('name', 'Platinum    Package')
        ->set('price', 2000.00)
        ->call('save')
        ->assertHasErrors('name');
});

it('can update an existing package', function () {
    $package = Package::factory()->create(['name' => 'Old Name', 'price' => 100.00]);

    Livewire::actingAs($this->user)
        ->test(PackageForm::class, ['package' => $package])
        ->set('name', 'New Pack Name')
        ->set('price', 150.00)
        ->call('save')
        ->assertRedirect(route('admin.manage-packages.index'));

    $this->assertDatabaseHas('packages', [
        'id' => $package->id,
        'name' => 'New Pack Name',
        'price' => 150.00,
    ]);
});
