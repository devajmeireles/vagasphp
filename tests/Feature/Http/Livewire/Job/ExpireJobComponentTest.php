<?php

use App\Enums\JobStatus;
use App\Http\Livewire\Job\ExpireComponent;
use App\Models\{Job, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('should be able to expire a job actived', function () {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->actived()
        ->for($user)
        ->create();

    Livewire::test(ExpireComponent::class, ['job' => $job])
        ->set('reason', 'A vaga foi preenchida')
        ->call('expire')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'success',
            'message' => 'Sucesso!',
            'text'    => "Procedimento realizado com sucesso",
        ]);

    $this->assertDatabaseHas('jobs', [
        'id'     => $job->id,
        'status' => JobStatus::Expired,
    ]);

    $this->assertNotNull($job->refresh()->deleted_at);

    $this->assertDatabaseHas('results', [
        'job_id'      => $job->id,
        'description' => 'A vaga foi preenchida',
    ]);
});

it('should not be able to expire job if it is not actived', function () {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->expired()
        ->for($user)
        ->create();

    Livewire::test(ExpireComponent::class, ['job' => $job])
        ->set('reason', 'A vaga foi preenchida')
        ->call('expire')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'error',
            'message' => 'Ops!',
            'text'    => "Esse anúncio não pode ser expirado.\nEle não esta ativo ou em revisão",
        ]);

    $this->assertTrue($job->isClean());
});

it('should be able to process validation rules successfully', function ($value, $rule) {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->expired()
        ->for($user)
        ->create();

    Livewire::test(ExpireComponent::class, ['job' => $job])
        ->set('reason', $value)
        ->call('expire')
        ->assertHasErrors([
            'reason' => $rule,
        ]);

    $this->assertTrue($job->isClean());
})->with([
    ['', 'required'],
    ['Haha', 'in'],
]);
