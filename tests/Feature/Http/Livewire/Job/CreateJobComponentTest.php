<?php

use App\Enums\{JobContent, JobModality, JobModel, JobSpecification, JobStatus, JobTypes};
use App\Http\Livewire\Job\CreateComponent;
use App\Jobs\CreateJob;
use App\Mail\Job\CreateJobMail;
use App\Models\{Job, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\{Mail, Queue};
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('should be able to redirect unauthenticated user', function () {
    $this->get(route('job.create'))
        ->assertRedirect(route('index'));
});

test('the page should contain livewire component successfully', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('job.create'))
        ->assertSeeLivewire(CreateComponent::class);
});

it('should be able to render component successfully', function () {
    Livewire::test(CreateComponent::class)
        ->assertSee('Formato')
        ->assertSee('Criar');
});

it('should be able to dispatch queue sucessfully', function () {
    $this->actingAs(User::factory()->create());

    $title         = "Quam ut at sed soluta architecto sapiente soaprans";
    $specification = JobSpecification::Laravel->value;
    $url           = fake()->url();
    $requirements  = [
        ['id' => 1, 'name' => 'Laravel'],
        ['id' => 2, 'name' => 'PHP'],
    ];

    Queue::fake();

    Livewire::test(CreateComponent::class)
        ->set('format', 'redirectable')
        ->set('job.title', $title)
        ->set('job.company', [
            'name' => 'Google',
            'site' => 'https://google.com',
        ])
        ->set('job.link', $url)
        ->set('job.specification', $specification)
        ->set('selected', $requirements)
        ->call('create')
        ->assertHasNoErrors();

    Queue::assertPushed(CreateJob::class);
});

it('should be able to create redirectable job sucessfully', function () {
    $this->actingAs($user = User::factory()->create());

    $title         = "Quam ut at sed soluta architecto sapiente soaprans";
    $specification = JobSpecification::Laravel->value;
    $url           = fake()->url();
    $requirements  = [
        ['id' => 1, 'name' => 'Laravel'],
        ['id' => 2, 'name' => 'PHP'],
    ];

    Mail::fake();

    Livewire::test(CreateComponent::class)
        ->set('format', 'redirectable')
        ->set('job.title', $title)
        ->set('job.link', $url)
        ->set('job.company', [
            'name' => 'Google',
            'site' => 'https://google.com',
        ])
        ->set('job.specification', $specification)
        ->set('selected', $requirements)
        ->call('create')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('redirect', [
            'route' => route('index'),
            'time'  => 5000,
        ]);

    $this->assertDatabaseHas('jobs', [
        'user_id' => $user->id,
        'title'   => $title,
        'company' => json_encode([
            'name' => 'Google',
            'site' => 'https://google.com',
        ]),
        'content'       => JobContent::Redirectable->value,
        'specification' => $specification,
        'link'          => $url,
        'requirement'   => json_encode(collect($requirements)->pluck('name')->toArray()),
    ]);

    Mail::assertQueued(function (CreateJobMail $mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

it('should be able to create detailable job sucessfully', function () {
    $this->actingAs($user = User::factory()->create());

    $title         = "Quam ut at sed soluta architecto sapiente soaprans";
    $description   = fake()->sentences(5, true);
    $email         = fake()->email();
    $specification = JobSpecification::Laravel->value;
    $requirements  = [
        ['id' => 1, 'name' => 'Laravel'],
        ['id' => 2, 'name' => 'PHP'],
    ];

    Livewire::test(CreateComponent::class)
        ->set('format', 'detailable')
        ->set('job.title', $title)
        ->set('job.description', $description)
        ->set('job.company', [
            'name' => 'Google',
            'site' => 'https://google.com',
        ])
        ->set('job.model', JobModel::Contract->value)
        ->set('job.type', JobTypes::Permanent->value)
        ->set('job.modality', JobModality::Remote->value)
        ->set('job.specification', $specification)
        ->set('job.remuneration', [
            'type'  => 'timing',
            'value' => 50,
        ])
        ->set('job.notification', $email)
        ->set('selected', $requirements)
        ->call('create')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('redirect', [
            'route' => route('index'),
            'time'  => 5000,
        ]);

    $this->assertDatabaseHas('jobs', [
        'user_id'       => $user->id,
        'title'         => $title,
        'description'   => $description,
        'content'       => JobContent::Detailable->value,
        'model'         => JobModel::Contract->value,
        'type'          => JobTypes::Permanent->value,
        'modality'      => JobModality::Remote->value,
        'specification' => $specification,
        'company'       => json_encode([
            'name' => 'Google',
            'site' => 'https://google.com',
        ]),
        'remuneration' => json_encode([
            'type'  => 'timing',
            'value' => 50,
        ]),
        'notification'  => $email,
        'configuration' => null,
        'requirement'   => json_encode(collect($requirements)->pluck('name')->toArray()),
    ]);
});

it('should be able to process validation rules sucessfully', function () {
    $this->actingAs(User::factory()->create());

    $specification = JobSpecification::Laravel->value;
    $requirements  = [
        ['id' => 1, 'name' => 'Laravel'],
        ['id' => 2, 'name' => 'PHP'],
    ];

    Livewire::test(CreateComponent::class)
        ->set('format', 'detailable')
        ->set('job.title', '')
        ->set('job.description', '')
        ->set('job.company', [
            'name' => null,
            'site' => 'asd',
        ])
        ->set('job.model', '')
        ->set('job.type', '')
        ->set('job.modality', '')
        ->set('job.specification', $specification)
        ->set('job.remuneration', [
            'type'  => '',
            'value' => null,
        ])
        ->set('job.notification', '')
        ->set('selected', $requirements)
        ->call('create')
        ->assertHasErrors([
            'job.title'             => 'required',
            'job.description'       => 'required',
            'job.model'             => 'required',
            'job.type'              => 'required',
            'job.company.name'      => 'required',
            'job.company.site'      => 'url',
            'job.remuneration.type' => 'required',
            'job.notification'      => 'required',
        ]);
});

test('a successfully created job with approved status should be displayed in the job list', function () {
    $this->actingAs(User::factory()->create());

    $title         = "Quam ut at sed soluta architecto sapiente soaprans";
    $specification = JobSpecification::Laravel->value;
    $url           = fake()->url();
    $requirements  = [
        ['id' => 1, 'name' => 'Laravel'],
        ['id' => 2, 'name' => 'PHP'],
    ];

    Livewire::test(CreateComponent::class)
        ->set('format', 'redirectable')
        ->set('job.title', $title)
        ->set('job.link', $url)
        ->set('job.company', [
            'name' => 'Google',
            'site' => 'https://google.com',
        ])
        ->set('job.specification', $specification)
        ->set('job.notification', fake()->email())
        ->set('selected', $requirements)
        ->call('create')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('redirect', [
            'route' => route('index'),
            'time'  => 5000,
        ]);

    $job = Job::query()->firstWhere('title', '=', $title);
    $job->update([
        'status' => JobStatus::Actived,
    ]);

    $this->get(route('index'))->assertSee($job->title);
});
