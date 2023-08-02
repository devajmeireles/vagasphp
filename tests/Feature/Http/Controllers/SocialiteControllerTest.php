<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\{GithubProvider, GitlabProvider};

uses(RefreshDatabase::class);

it('should be able to redirects to the github', function ($provider) {
    $this->get(route('socialite.redirect', $provider))
        ->assertRedirect()
        ->assertStatus(302);
})->with([
    'github',
    'google',
]);

it('should be able to login by google oauth', function ($provider) {
    Socialite::shouldReceive('driver->user')
        ->andReturn($user = User::factory()->create());

    $this->get(route('socialite.callback', $provider))
         ->assertRedirect(route('index'));

    $this->assertAuthenticatedAs($user);
})->with([
    'github',
    'google',
]);
