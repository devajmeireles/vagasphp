<?php

use App\Enums\JobContent;
use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('shortened url should access a job successfully', function () {
    $job = Job::factory()
        ->actived()
        ->create([
            'content' => JobContent::Detailable,
            'link'    => Str::random(10),
        ]);

    $this->get(route('shortened', $job->link))
        ->assertRedirect(route('job.view', $job));

    /** @var Job $cache */
    $cache = Cache::get(sprintf('job::shortened::%s', $job->link));

    expect($cache)
        ->toBeInstanceOf(Job::class)
        ->and($cache->is($job));
});

test('inexistent shortened url should not be found', function () {
    $this->get(route('shortened', Str::random(10)))
        ->assertNotFound();
});
