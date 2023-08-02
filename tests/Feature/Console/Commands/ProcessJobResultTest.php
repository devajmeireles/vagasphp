<?php

use App\Console\Commands\ProcessJobResult;
use App\Enums\JobContent;
use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('command should works successfully', function () {
    $job = Job::factory()
        ->actived()
        ->create([
            'content' => JobContent::Detailable,
            'result'  => 0,
        ]);

    $this->get(route('job.view', $job))
         ->assertSuccessful();

    $this->artisan(ProcessJobResult::class)
        ->assertSuccessful();

    $this->assertDatabaseHas('jobs', [
        'id'     => $job->id,
        'result' => 1,
    ]);
});
