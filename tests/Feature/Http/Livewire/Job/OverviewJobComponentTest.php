<?php

use App\Enums\{JobContent, JobModality, JobModel, JobSpecification};
use App\Http\Livewire\Job\OverviewComponent;
use App\Models\{Job, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('should be able to render jobs sucessfully', function () {
    $job = Job::factory()
        ->actived()
        ->create();

    $this->get(route('index'))
        ->assertSee($job->title);
});

it('should be able to render jobs results sucessfully', function () {
    $job = Job::factory()
        ->actived()
        ->create([
            'content' => JobContent::Detailable,
            'result'  => 12542,
        ]);

    $this->get(route('index'))
        ->assertSee($job->title)
        ->assertSee(12542);
});

it('should be able to filter jobs by specification sucessfully', function () {
    $laravel = Job::factory()
        ->actived()
        ->create([
            'specification' => JobSpecification::Laravel,
        ]);

    $ci = Job::factory()
        ->actived()
        ->create([
            'specification' => JobSpecification::CodeIgniter,
        ]);

    Livewire::test(OverviewComponent::class)
        ->assertSee($laravel->title)
        ->assertSee($ci->title)
        ->set('filters', [JobSpecification::CodeIgniter->value])
        ->assertDontSee($laravel->title)
        ->assertSee($ci->title);
});

it('should be able to filter jobs by authenticated sucessfully', function () {
    $this->actingAs($user = User::factory()->create());

    $laravel = Job::factory()
        ->actived()
        ->for($user)
        ->create([
            'specification' => JobSpecification::Laravel,
        ]);

    $ci = Job::factory()
        ->actived()
        ->create([
            'specification' => JobSpecification::CodeIgniter,
        ]);

    Livewire::test(OverviewComponent::class)
        ->assertSee($laravel->title)
        ->assertSee($ci->title)
        ->set('authenticated', true)
        ->assertSee($laravel->title)
        ->assertDontSee($ci->title);
});

it('should be able to load more jobs successfully', function () {
    $first = Job::factory(5)
        ->actived()
        ->create();

    $component = Livewire::test(OverviewComponent::class);

    $first->each(fn (Job $job) => $component->assertSee($job->title));

    $second = Job::factory(5)
        ->actived()
        ->create();

    $component->call('load');

    $second->each(fn (Job $job) => $component->assertSee($job->title));
});

it('should be able to filter for contract only', function () {
    $contract = Job::factory()
        ->actived()
        ->create([
            'model' => JobModel::Contract,
        ]);

    $clt = Job::factory()
        ->actived()
        ->create([
            'model' => JobModel::Clt,
        ]);

    Livewire::test(OverviewComponent::class)
        ->assertSee($contract->title)
        ->assertSee($clt->title)
        ->set('contract', true)
        ->assertSee($contract->title)
        ->assertDontSee($clt->title);
});

it('should be able to filter for remote only', function () {
    $remote = Job::factory()
        ->actived()
        ->create([
            'modality' => JobModality::Remote,
        ]);

    $presential = Job::factory()
        ->actived()
        ->create([
            'modality' => JobModality::Presential,
        ]);

    Livewire::test(OverviewComponent::class)
        ->assertSee($remote->title)
        ->assertSee($presential->title)
        ->set('remote', true)
        ->assertSee($remote->title)
        ->assertDontSee($presential->title);
});
