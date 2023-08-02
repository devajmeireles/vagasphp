<?php

use App\Enums\JobStatus;
use App\Http\Livewire\User\DestroyProfileComponent;
use App\Models\{Candidacy, Job, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('should be able to destroy profile successfully', function () {
    $this->actingAs($user = User::factory()->create());

    Livewire::test(DestroyProfileComponent::class, ['user' => $user])
        ->call('destroy')
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'success',
            'message' => 'Vazou!',
            'text'    => 'Esperamos te ver em breve ... Até mais!',
        ]);

    $this->assertGuest();
    $this->assertModelMissing($user);
});

it('should be able to open alert successfully', function () {
    $this->actingAs($user = User::factory()->create());

    Livewire::test(DestroyProfileComponent::class, ['user' => $user])
        ->call('confirmingBeforeDestroy')
        ->assertDispatchedBrowserEvent('swal:confirm', [
            'type'        => 'warning',
            'message'     => 'Sério?',
            'text'        => 'Você tem certeza que deseja fazer isso?',
            'confirm'     => 'destroy::profile::component::destroy',
            'cancel'      => 'destroy::profile::component::cancel',
            'dismissable' => false,
        ]);
});

it('should not be able to destroy profile with jobs', function (JobStatus $status) {
    $this->actingAs($user = User::factory()->create());

    Job::factory()
        ->for($user)
        ->create([
            'status' => $status,
        ]);

    Livewire::test(DestroyProfileComponent::class, ['user' => $user])
        ->call('destroy')
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'error',
            'message' => 'Ops!',
            'text'    => 'Encerre os anúncios ativos ou em revisão.',
        ]);
})->with([
    [JobStatus::Actived],
    [JobStatus::Review],
]);

it('should not be able to destroy profile with candidacies', function () {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->for($user)
        ->create([
            'status' => JobStatus::Actived,
        ]);

    Candidacy::factory()
        ->for($user)
        ->for($job)
        ->create();

    Livewire::test(DestroyProfileComponent::class, ['user' => $user])
        ->call('destroy')
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'error',
            'message' => 'Ops!',
            'text'    => 'Cancele todas as suas candidaturas.',
        ]);
});
