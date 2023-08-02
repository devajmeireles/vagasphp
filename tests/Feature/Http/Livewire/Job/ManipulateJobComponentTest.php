<?php

use App\Http\Livewire\Job\{ManipulateComponent, OverviewComponent};
use App\Models\{Job, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('should be able to destroy job', function () {
    $this->actingAs($user = User::factory()->create());

    $completed = Job::factory()
        ->completed()
        ->for($user)
        ->create();

    $actived = Job::factory()
        ->actived()
        ->for($user)
        ->create();

    Livewire::test(ManipulateComponent::class, ['job' => $completed])
        ->call('destroy')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'success',
            'message' => 'Sucesso!',
            'text'    => "Procedimento realizado com sucesso",
        ]);

    $this->assertDatabaseCount('jobs', 1);
    $this->assertModelMissing($completed);
    $this->assertModelExists($actived);

    Livewire::test(OverviewComponent::class)
        ->assertDontSee($completed->title);
});

it('should be able to destroy pending job', function () {
    $this->actingAs($user = User::factory()->create());

    $pending = Job::factory()
        ->pending()
        ->for($user)
        ->create();

    $actived = Job::factory()
        ->actived()
        ->for($user)
        ->create();

    Livewire::test(ManipulateComponent::class, ['job' => $pending])
        ->call('destroy')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'success',
            'message' => 'Sucesso!',
            'text'    => "Procedimento realizado com sucesso",
        ]);

    $this->assertModelMissing($pending);
    $this->assertModelExists($actived);
});

it('should not be able to destroy actived job', function () {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->actived()
        ->for($user)
        ->create();

    Livewire::test(ManipulateComponent::class, ['job' => $job])
        ->call('destroy')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'error',
            'message' => 'Ops!',
            'text'    => "Realize o encerramento do anúncio.",
        ]);

    $this->assertTrue($job->isClean());
});
