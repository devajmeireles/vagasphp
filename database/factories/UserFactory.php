<?php

namespace Database\Factories;

use App\Enums\SocialiteProvider;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name'              => $this->faker->name(),
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'provider'          => $this->faker->randomElement(SocialiteProvider::toArray()),
            'remember_token'    => Str::random(10),
        ];
    }

    public function admin(): self
    {
        return $this->state(['is_admin' => true]);
    }

    public function normal(): self
    {
        return $this->state(['is_admin' => false]);
    }

    public function unverified(): self
    {
        return $this->state(['email_verified_at' => null]);
    }
}
