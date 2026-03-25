<?php

use App\Livewire\Categories\CategoryForm;
use App\Models\Category;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the create category page', function () {
    $this->actingAs($this->user)
        ->get(route('admin.categories.create'))
        ->assertOk()
        ->assertSee('Define Collection');
});

it('validates category name', function () {
    Livewire::actingAs($this->user)
        ->test(CategoryForm::class)
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['name' => 'required']);
});

it('can create a new category', function () {
    Livewire::actingAs($this->user)
        ->test(CategoryForm::class)
        ->set('name', 'Gourmet Selection')
        ->call('save')
        ->assertRedirect(route('admin.categories.index'));

    $this->assertDatabaseHas('categories', [
        'name' => 'Gourmet Selection',
        'slug' => 'gourmet-selection',
    ]);
});

it('prevents duplicate category names similar to existing ones', function () {
    Category::factory()->create(['name' => 'Gourmet Selection', 'slug' => 'gourmet-selection']);

    Livewire::actingAs($this->user)
        ->test(CategoryForm::class)
        ->set('name', 'Gourmet     Selection') // should slugify to same value
        ->call('save')
        ->assertHasErrors('name');
});

it('can update an existing category', function () {
    $category = Category::factory()->create(['name' => 'Old Name']);

    Livewire::actingAs($this->user)
        ->test(CategoryForm::class, ['category' => $category])
        ->set('name', 'New Name')
        ->call('save')
        ->assertRedirect(route('admin.categories.index'));

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'New Name',
        'slug' => 'new-name',
    ]);
});
