<?php

use App\Enums\JobStatus;
use App\Http\Livewire\User\CandidacyComponent;
use App\Models\{Candidacy, Job, User};
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should be able to render candidacies successfully', function () {
    $this->actingAs($user = User::factory()->create());

    $candidacy = Candidacy::factory()
        ->for($user)
        ->create();

    Livewire::test(CandidacyComponent::class, ['user' => $user])
        ->assertSee($candidacy->only('title'));
});

it('should be able to open alert successfully', function () {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->for($user)
        ->actived()
        ->create();

    Candidacy::factory()
        ->for($job)
        ->for($user)
        ->create();

    Livewire::test(CandidacyComponent::class, ['user' => $user])
        ->call('confirmingBeforeCancel', $job)
        ->assertDispatchedBrowserEvent('swal:confirm', [
            'type'        => 'warning',
            'message'     => 'Atenção!',
            'text'        => 'Você tem certeza que deseja prosseguir?',
            'confirm'     => 'candidacy::component::cancel',
            'cancel'      => 'candidacy::component::preserve',
            'append'      => ['job' => $job->id],
            'dismissable' => false,
        ]);
});

it('should be able to cancel candidacy successfully', function () {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->for($user)
        ->create([
            'status' => JobStatus::Actived,
        ]);

    Candidacy::factory()
        ->for($job)
        ->for($user)
        ->create();

    Livewire::test(CandidacyComponent::class, ['user' => $user])
        ->call('cancel', ['job' => $job->id])
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'success',
            'message' => 'Sucesso!',
            'text'    => 'Procedimento realizado com sucesso',
        ]);
});

//TODO: add notification tests
