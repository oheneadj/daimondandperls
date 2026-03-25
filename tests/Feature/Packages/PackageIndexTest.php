<?php

use App\Livewire\Packages\PackageIndex;
use App\Models\Category;
use App\Models\Package;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the package index page', function () {
    $this->actingAs($this->user)
        ->get(route('admin.manage-packages.index'))
        ->assertOk()
        ->assertSee('All Packages');
});

it('lists existing packages', function () {
    Package::factory()->create(['name' => 'Premium Buffet']);

    Livewire::actingAs($this->user)
        ->test(PackageIndex::class)
        ->assertSee('Premium Buffet');
});

it('can search packages', function () {
    Package::factory()->create(['name' => 'Premium Buffet']);
    Package::factory()->create(['name' => 'Basic Snack']);

    Livewire::actingAs($this->user)
        ->test(PackageIndex::class)
        ->set('search', 'Premium')
        ->assertSee('Premium Buffet')
        ->assertDontSee('Basic Snack');
});

it('can filter packages by category', function () {
    $category1 = Category::factory()->create(['name' => 'Category A']);
    $category2 = Category::factory()->create(['name' => 'Category B']);

    Package::factory()->create(['name' => 'Package A', 'category_id' => $category1->id]);
    Package::factory()->create(['name' => 'Package B', 'category_id' => $category2->id]);

    Livewire::actingAs($this->user)
        ->test(PackageIndex::class)
        ->set('categoryId', $category1->id)
        ->assertSee('Package A')
        ->assertDontSee('Package B');
});

it('can toggle package status', function () {
    $package = Package::factory()->create(['name' => 'Test Pkg', 'is_active' => true]);

    Livewire::actingAs($this->user)
        ->test(PackageIndex::class)
        ->call('toggleActive', $package->id);

    expect($package->fresh()->is_active)->toBeFalse();
});

it('can soft delete a package without bookings', function () {
    $package = Package::factory()->create(['name' => 'Test Pkg']);

    Livewire::actingAs($this->user)
        ->test(PackageIndex::class)
        ->call('confirmDelete', $package->id)
        ->call('deletePackage')
        ->assertDispatched('banner');

    $this->assertSoftDeleted('packages', ['id' => $package->id]);
});
