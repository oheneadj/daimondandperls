<?php

use App\Livewire\Pages\HomePage;
use App\Models\Category;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('renders the homepage successfully', function () {
    $this->get('/')->assertStatus(200);
});

it('displays active packages on the homepage', function () {
    $category = Category::factory()->create();
    $package = Package::factory()->create([
        'category_id' => $category->id,
        'name' => 'Jollof Supreme',
        'is_active' => true,
    ]);

    Livewire::test(HomePage::class)
        ->assertSee('Jollof Supreme');
});

it('does not display inactive packages', function () {
    $category = Category::factory()->create();
    Package::factory()->create([
        'category_id' => $category->id,
        'name' => 'Hidden Package',
        'is_active' => false,
    ]);

    Livewire::test(HomePage::class)
        ->assertDontSee('Hidden Package');
});

it('filters packages by category', function () {
    $categoryA = Category::factory()->create(['name' => 'Rice']);
    $categoryB = Category::factory()->create(['name' => 'Grills']);

    $packageA = Package::factory()->create([
        'category_id' => $categoryA->id,
        'name' => 'Party Jollof',
        'is_active' => true,
    ]);
    $packageB = Package::factory()->create([
        'category_id' => $categoryB->id,
        'name' => 'BBQ Platter',
        'is_active' => true,
    ]);

    Livewire::test(HomePage::class)
        ->assertSee('Party Jollof')
        ->assertSee('BBQ Platter')
        ->call('selectCategory', $categoryA->id)
        ->assertSee('Party Jollof')
        ->assertDontSee('BBQ Platter')
        ->call('selectCategory', null)
        ->assertSee('Party Jollof')
        ->assertSee('BBQ Platter');
});

it('renders without errors when no packages exist', function () {
    Livewire::test(HomePage::class)
        ->assertStatus(200)
        ->assertSee('No packages available');
});

it('displays key homepage sections', function () {
    Livewire::test(HomePage::class)
        ->assertSee('How it works')
        ->assertSee('Events we cater for')
        ->assertSee('What our clients say')
        ->assertSee('Frequently Asked Questions');
});
