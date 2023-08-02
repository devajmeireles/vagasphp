<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should not be able to be accessed for guests', function () {
    $this->get(route('me'))
        ->assertRedirect(route('index'));
});

it('should be able to be accessed for auth users', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('me'))
        ->assertSee($user->only('name', 'email'));

    $this->assertAuthenticatedAs($user);
});
