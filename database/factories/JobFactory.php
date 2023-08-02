<?php

namespace Database\Factories;

use App\Enums\{JobContent, JobModality, JobModel, JobSpecification, JobStatus, JobTypes};
use App\Models\{Job, User};
use Faker\Provider\Lorem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition(): array
    {
        $content = $this->faker->randomElement(JobContent::toArray());

        return [
            'user_id'       => User::factory(),
            'specification' => $this->faker->randomElement(JobSpecification::toArray()),
            'status'        => $this->faker->randomElement(JobStatus::toArray()),
            'type'          => $this->faker->randomElement(JobTypes::toArray()),
            'content'       => $content,
            'model'         => $this->faker->randomElement(JobModel::toArray()),
            'title'         => Lorem::text(50),
            'description'   => Lorem::text(500),
            'company'       => [
                'name' => $this->faker->company(),
                'site' => $this->faker->url(),
            ],
            'modality'     => $this->faker->randomElement(JobModality::toArray()),
            'link'         => $content == 1 ? $this->faker->url() : Str::random(10),
            'remuneration' => [
                'type'  => $this->faker->randomElement(['fix', 'timing']),
                'value' => $this->faker->randomFloat(2, 1000, 10000),
            ],
            'requirement' => $this->faker->randomElements([
                'php',
                'mysql',
                'laravel',
                'javascript',
                'tailwindcss',
            ], 3),
            'configuration' => [
                'github' => $this->faker->randomElement([true, false]),
                'resume' => $this->faker->randomElement([true, false]),
            ],
            'result'   => $this->faker->randomNumber(3),
            'priority' => $this->faker->randomElement([1, 2, 3, 4, 5]),
        ];
    }

    public function pending(): self
    {
        return $this->state([
            'status'     => JobStatus::Review,
            'deleted_at' => null,
        ]);
    }

    public function actived(): self
    {
        return $this->state([
            'status'     => JobStatus::Actived,
            'deleted_at' => null,
        ]);
    }

    public function expired(): self
    {
        return $this->state([
            'status'     => JobStatus::Expired,
            'link'       => null,
            'deleted_at' => now(),
        ]);
    }

    public function completed(): self
    {
        return $this->state([
            'status'     => JobStatus::Completed,
            'link'       => null,
            'deleted_at' => now(),
        ]);
    }

    public function remuneration(string $type): self
    {
        return $this->state(function () use ($type) {
            $min = $type === 'fix' ? 1000 : 30;
            $max = $type === 'fix' ? 10000 : 50;

            return [
                'remuneration' => [
                    'type'  => $type,
                    'value' => rand($min, $max),
                ],
            ];
        });
    }

    public function notification(?string $notification = null): self
    {
        return $this->state(['notification' => $notification ?? fake()->email()]);
    }
}
