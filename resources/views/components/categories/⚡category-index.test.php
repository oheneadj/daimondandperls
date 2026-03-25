<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('categories.category-index')
        ->assertStatus(200);
});
