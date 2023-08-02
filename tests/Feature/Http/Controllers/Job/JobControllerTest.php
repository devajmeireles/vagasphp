<?php

use App\Enums\{JobContent, JobStatus};
use App\Models\{Job, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

test('a url shortened should access a job successfully', function () {
    $job = Job::factory()
        ->actived()
        ->create([
            'content' => JobContent::Detailable,
        ]);

    $this->get(route('job.view', $job))
        ->assertSuccessful()
        ->assertSee($job->title)
        ->assertSee($job->remuneration())
        ->assertSee($job->created_at->format('d/m/Y H:i'));
});

it('should be able to redirects a job redirectable successfully', function () {
    $job = Job::factory()
        ->actived()
        ->create([
            'content' => JobContent::Redirectable,
            'link'    => 'https://google.com',
        ]);

    $this->get(route('job.view', $job))
        ->assertRedirect($job->link);
});

it('should be able to increase job cache successfully', function () {
    $job = Job::factory()
        ->actived()
        ->create([
            'content' => JobContent::Detailable,
            'result'  => 0,
        ]);

    $this->get(route('job.view', $job))
        ->assertSuccessful();

    expect(Cache::get('job::visualization'))
        ->toBeArray()
        ->toBe([
            $job->id => 1,
        ]);
});

it('should be able to display alert successfully', function () {
    $job = Job::factory()
        ->actived()
        ->create([
            'content'    => JobContent::Detailable,
            'result'     => 0,
            'created_at' => now()->subMonth(),
        ]);

    $this->get(route('job.view', $job))
        ->assertSuccessful()
        ->assertSee('Esta vaga foi criada há mais de um mês atrás.');
});

it('should not be able to display alert', function () {
    $job = Job::factory()
        ->actived()
        ->create([
            'content'    => JobContent::Detailable,
            'result'     => 0,
            'created_at' => now()->subDays(15),
        ]);

    $this->get(route('job.view', $job))
        ->assertSuccessful()
        ->assertDontSee('Esta vaga foi criada há mais de um mês atrás.');
});

it('should not be able to increase job result if auth user is job owner', function () {
    $user = User::factory()->create();

    $job = Job::factory()
        ->actived()
        ->for($user)
        ->create([
            'content' => JobContent::Detailable,
            'result'  => 0,
        ]);

    $this->get(route('job.view', $job))
        ->assertSuccessful();

    $this->assertEquals(0, $job->refresh()->result);
});

it('should not be able to render a job with status diff than actived', function (JobStatus $status) {
    $job = Job::factory()
        ->create([
            'status' => $status,
        ]);
    $this->get(route('job.view', $job))
         ->assertForbidden();
})->with([
    JobStatus::Review,
    JobStatus::Completed,
]);

it('should not be able to render edit element for jobs that is not actived', function () {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->pending()
        ->for($user)
        ->create();

    $this->get(route('job.view', $job))
         ->assertDontSee('Editar Anúncio');
});

it('should be able to render a job with status diff than actived but auth is owner', function (JobStatus $status) {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->for($user)
        ->create([
            'status' => $status,
        ]);

    $this->get(route('job.view', $job))
         ->assertSuccessful()
         ->assertDontSee('Editar Anúncio');
})->with([
    JobStatus::Review,
    JobStatus::Completed,
]);
