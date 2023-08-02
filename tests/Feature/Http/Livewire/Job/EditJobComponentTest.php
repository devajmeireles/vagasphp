<?php

use App\Enums\{JobContent, JobModality, JobModel, JobSpecification, JobStatus, JobTypes};
use App\Http\Livewire\Job\EditComponent;
use App\Mail\Job\EditJobMail;
use App\Models\{Job, User};
use Faker\Provider\Lorem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('should be able to forbid edition', function () {
    $this->actingAs(User::factory()->create());

    $job = Job::factory()
        ->actived()
        ->for(User::factory()->create())
        ->create([
            'content' => JobContent::Detailable,
            'result'  => 12542,
        ]);

    $this->get(route('job.edit', $job))
         ->assertForbidden();
});

it('should be able to render alert for job not actived', function () {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->actived()
        ->for($user)
        ->create([
            'status'  => JobStatus::Review,
            'content' => JobContent::Detailable,
            'result'  => 12542,
        ]);

    $this->get(route('job.edit', $job))
         ->assertSuccessful()
         ->assertSeeLivewire(EditComponent::class)
         ->assertSee($job->only('title', 'description'))
         ->assertSee('não está ativo');
});

it('should be able to render job for edition', function () {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->actived()
        ->for($user)
        ->create([
            'content' => JobContent::Detailable,
            'result'  => 12542,
        ]);

    $this->get(route('job.edit', $job))
         ->assertSuccessful()
         ->assertSeeLivewire(EditComponent::class)
         ->assertSee($job->only('title', 'description'));
});

it('should be able to cancel edition and refresh job', function () {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->actived()
        ->for($user)
        ->create([
            'content' => JobContent::Detailable,
            'result'  => 12542,
        ]);

    $title       = Lorem::text(100);
    $description = fake()->paragraphs(10, true);

    Livewire::test(EditComponent::class, ['job' => $job])
        ->set('job.title', $title)
        ->set('job.description', $description)
        ->call('cancel')
        ->assertNotSet('job.title', $title)
        ->assertNotSet('job.description', $description)
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'success',
            'message' => 'Sucesso!',
            'text'    => "Edição cancelada com sucesso.",
        ]);

    $this->assertDatabaseMissing('jobs', [
        'user_id'     => $user->id,
        'title'       => $title,
        'description' => $description,
    ]);
});

it('should be able to edit job', function () {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->actived()
        ->notification()
        ->for($user)
        ->create([
            'content' => JobContent::Detailable,
            'result'  => 12542,
        ]);

    $title         = "Quam ut at sed soluta architecto sapiente soaprans";
    $description   = fake()->paragraphs(10, true);
    $type          = JobTypes::Internship->value;
    $specification = JobSpecification::WordPress->value;
    $model         = JobModel::Contract->value;
    $modality      = JobModality::Remote->value;
    $remuneration  = [
        'type'  => 'fix',
        'value' => 1000,
    ];
    $requirements = [
        ['id' => 1, 'name' => 'Laravel'],
        ['id' => 2, 'name' => 'PHP'],
        ['id' => 3, 'name' => 'WordPress'],
        ['id' => 4, 'name' => 'API'],
    ];
    $company = [
        'name' => fake()->company(),
        'site' => fake()->url(),
    ];

    Mail::fake();

    Livewire::test(EditComponent::class, ['job' => $job])
        ->set('job.title', $title)
        ->set('job.description', $description)
        ->set('job.type', $type)
        ->set('job.specification', $specification)
        ->set('job.model', $model)
        ->set('job.modality', $modality)
        ->set('job.remuneration.type', $remuneration['type'])
        ->set('job.remuneration.value', $remuneration['value'])
        ->set('job.company.name', $company['name'])
        ->set('job.company.site', $company['site'])
        ->set('job.notification', $job->notification)
        ->set('selected', $requirements)
        ->call('update')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'success',
            'message' => 'Formulário Enviado!',
            'text'    => "Os dados serão submetidos a uma analise.\nVocê receberá uma resposta em seu e-mail.",
        ]);

    $this->assertDatabaseHas('jobs', [
        'user_id'       => $user->id,
        'title'         => $title,
        'description'   => $description,
        'status'        => JobStatus::Review,
        'type'          => $type,
        'specification' => $specification,
        'model'         => $model,
        'modality'      => $modality,
        'remuneration'  => json_encode($remuneration),
        'company'       => json_encode($company),
        'requirement'   => json_encode(collect($requirements)->pluck('name')->toArray()),
    ]);

    Mail::assertQueued(function (EditJobMail $mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

it('should be able to edit job configuration', function () {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->actived()
        ->notification()
        ->for($user)
        ->create([
            'content'      => JobContent::Detailable,
            'result'       => 12542,
            'remuneration' => [
                'type'  => 'fix',
                'value' => 5000,
            ],
        ]);

    $configuration = [
        'github' => false,
        'resume' => true,
    ];

    Livewire::test(EditComponent::class, ['job' => $job])
        ->set('job.configuration.github', $configuration['github'])
        ->set('job.configuration.resume', $configuration['resume'])
        ->call('update')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'success',
            'message' => 'Formulário Enviado!',
            'text'    => "Os dados serão submetidos a uma analise.\nVocê receberá uma resposta em seu e-mail.",
        ]);

    $this->assertDatabaseHas('jobs', [
        'id'            => $job->id,
        'user_id'       => $user->id,
        'configuration' => json_encode($configuration),
    ]);
});

it('should be able to edit job notification', function ($notification) {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->actived()
        ->notification()
        ->for($user)
        ->create([
            'content'      => JobContent::Detailable,
            'result'       => 12542,
            'remuneration' => [
                'type'  => 'fix',
                'value' => 5000,
            ],
        ]);

    Livewire::test(EditComponent::class, ['job' => $job])
        ->set('job.notification', $notification)
        ->call('update')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'success',
            'message' => 'Formulário Enviado!',
            'text'    => "Os dados serão submetidos a uma analise.\nVocê receberá uma resposta em seu e-mail.",
        ]);

    $this->assertDatabaseHas('jobs', [
        'user_id'      => $user->id,
        'notification' => $notification,
    ]);
})->with([
    ['job@job.com'],
    ['https://hooks.slack.com/services/1234567890/1234567890/1234567890'],
]);

it('should not be able to render edit button for jobs in review', function () {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->pending()
        ->for($user)
        ->create([
            'content' => JobContent::Detailable,
            'result'  => 12542,
        ]);

    $this->get(route('job.edit', $job))
         ->assertSuccessful()
         ->assertDontSee('Salvar');
});

it('should be not able to edit job with status diff than actived', function (JobStatus $status) {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->actived()
        ->for($user)
        ->create([
            'status'       => $status,
            'content'      => JobContent::Detailable,
            'result'       => 12542,
            'remuneration' => [
                'type'  => 'fix',
                'value' => 5000,
            ],
        ]);

    Livewire::test(EditComponent::class, ['job' => $job])
        ->set('job.notification', fake()->email())
        ->call('update')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'error',
            'message' => 'Ops!',
            'text'    => "Este anúncio não está ativo.",
        ]);
})->with([
    JobStatus::Review,
    JobStatus::Completed,
    JobStatus::Expired,
]);

it('should be able to process validation rules sucessfully', function () {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->actived()
        ->for($user)
        ->create([
            'content' => JobContent::Detailable,
            'result'  => 12542,
        ]);

    Livewire::test(EditComponent::class, ['job' => $job])
        ->set('job.title', 'Quam ut at sed soluta architecto sapiente soaprans-Quam ut at sed soluta architecto sapiente soaprans')
        ->set('job.description', '')
        ->set('job.remuneration.type', 'fix')
        ->set('job.remuneration.value', '')
        ->set('job.company.name', '')
        ->set('job.company.site', fake()->sentence())
        ->call('update')
        ->assertHasErrors([
            'job.title'              => 'max',
            'job.description'        => 'required',
            'job.company.name'       => 'required',
            'job.company.site'       => 'url',
            'job.remuneration.value' => 'required',
        ]);
});
