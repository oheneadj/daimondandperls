<?php

use App\Livewire\Admin\Users\UserShow;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create([
        'type' => \App\Enums\UserType::Admin,
        'is_active' => true,
    ]);
    
    $this->actingAs($this->user);
});

test('it can render the user show page', function () {
    $otherUser = User::factory()->create();
    
    Livewire::test(UserShow::class, ['user' => $otherUser])
        ->assertStatus(200)
        ->assertSee($otherUser->name)
        ->assertSee($otherUser->email);
});
