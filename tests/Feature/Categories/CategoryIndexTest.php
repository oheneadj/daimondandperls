<?php

use App\Livewire\Categories\CategoryIndex;
use App\Models\Category;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the category index page', function () {
    $this->actingAs($this->user)
        ->get(route('admin.categories.index'))
        ->assertOk()
        ->assertSee('Culinary Collections');
});

it('lists existing categories', function () {
    $category = Category::factory()->create(['name' => 'Traditional']);

    Livewire::actingAs($this->user)
        ->test(CategoryIndex::class)
        ->assertSee('Traditional');
});

it('can search categories', function () {
    Category::factory()->create(['name' => 'Traditional']);
    Category::factory()->create(['name' => 'Continental']);

    Livewire::actingAs($this->user)
        ->test(CategoryIndex::class)
        ->set('search', 'Cont')
        ->assertSee('Continental')
        ->assertDontSee('Traditional');
});

it('can soft delete a category', function () {
    $category = Category::factory()->create(['name' => 'Traditional']);

    Livewire::actingAs($this->user)
        ->test(CategoryIndex::class)
        ->call('deleteCategory', $category->id)
        ->assertDispatched('banner');

    $this->assertSoftDeleted('categories', ['id' => $category->id]);
});
