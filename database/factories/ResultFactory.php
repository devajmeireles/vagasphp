<?php

namespace Database\Factories;

use App\Enums\JobResult;
use App\Models\{Job, Result};
use Illuminate\Database\Eloquent\Factories\Factory;

class ResultFactory extends Factory
{
    protected $model = Result::class;

    public function definition(): array
    {
        return [
            'job_id'      => Job::factory(),
            'type'        => $this->faker->randomElement(JobResult::toArray()),
            'description' => $this->faker->text(),
        ];
    }
}
