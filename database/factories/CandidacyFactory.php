<?php

namespace Database\Factories;

use App\Models\{Candidacy, Job, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidacyFactory extends Factory
{
    protected $model = Candidacy::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'job_id'  => Job::factory(),
        ];
    }
}
