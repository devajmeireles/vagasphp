<?php

use App\Enums\JobStatus;
use App\Http\Livewire\User\ResumeComponent;
use App\Models\{Candidacy, Job, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('should be able to upload resume successfully', function () {
    $this->actingAs($user = User::factory()->create());

    Storage::fake('resumes');

    $fake = UploadedFile::fake();

    Livewire::test(ResumeComponent::class, ['user' => $user])
        ->set('resume', $fake->create('resume.pdf'))
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'success',
            'message' => 'Sucesso!',
            'text'    => "Currículo enviado com sucesso!",
        ]);
});

it('should be able to remove resume successfully', function () {
    $name = sprintf('%s.pdf', Str::lower(Str::random(20)));
    $copy = rescue(fn () => copy(base_path('tests/Fixtures/test.pdf'), storage_path('app/public/resumes/' . $name)), false);

    if ($copy === false) {
        $this->markTestSkipped('could not copy file');
    }

    $this->actingAs($user = User::factory()->create([
        'resume' => $name,
    ]));

    Livewire::test(ResumeComponent::class, ['user' => $user])
        ->call('remove')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'success',
            'message' => 'Sucesso!',
            'text'    => "Currículo removido com sucesso!",
        ]);

    expect($user->fresh()->resume)->toBeNull();
});

it('should be able to process validation rules successfully', function () {
    $this->actingAs($user = User::factory()->create());

    Storage::fake('resumes');

    $fake = UploadedFile::fake();

    Livewire::test(ResumeComponent::class, ['user' => $user])
        ->set('resume', $fake->create('resume.png'))
        ->assertHasErrors([
            'resume' => 'mimes:pdf',
        ]);

    Livewire::test(ResumeComponent::class, ['user' => $user])
        ->set('resume', $fake->create('resume.pdf', 10000000))
        ->assertHasErrors([
            'resume',
        ]);
});

it('should not be able to remove resume with pending candidacy', function () {
    $name = sprintf('%s.pdf', Str::lower(Str::random(20)));
    $copy = rescue(fn () => copy(base_path('tests/Fixtures/test.pdf'), storage_path('app/public/resumes/' . $name)), false);

    if ($copy === false) {
        $this->markTestSkipped('could not copy file');
    }

    $this->actingAs($user = User::factory()->create(['resume' => $name]));

    $job = Job::factory()
        ->for($user)
        ->create([
            'status' => JobStatus::Actived,
        ]);

    Candidacy::factory()
        ->for($user)
        ->for($job)
        ->create();

    Livewire::test(ResumeComponent::class, ['user' => $user])
        ->call('remove')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'error',
            'message' => 'Ops!',
            'text'    => "Você não pode remover o seu currículo\nenquanto houver candidaturas em andamento.",
        ]);

    expect($user->fresh()->resume)
        ->not()
        ->toBeNull();
});
