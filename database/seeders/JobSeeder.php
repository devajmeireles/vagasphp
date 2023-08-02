<?php

namespace Database\Seeders;

use App\Models\{Job, User};
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create();

        // It had to be done this way because apparently Laravel cannot use multiple creations at the
        // same time as the `createQuietly` method is used. Without this method, several emails are
        // triggered by the Observer when the Job is created.
        collect(range(1, 100))
            ->each(function () use ($user) {
                Job::factory()
                    ->for($user)
                    ->remuneration('timing')
                    ->createQuietly([
                        'id' => fake()->uuid(),
                    ]);
            });
    }
}
