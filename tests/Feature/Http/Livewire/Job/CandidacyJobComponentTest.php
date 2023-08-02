<?php

use App\Channels\SlackChannel;
use App\Enums\JobContent;
use App\Events\Job\JobCandidacy;
use App\Http\Livewire\Job\CandidacyComponent;
use App\Models\{Job, User};
use App\Notifications\CandidacyJobNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\{Event, Notification, Queue};
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('component should be able to get auth user successfully', function () {
    $this->actingAs($user = User::factory()->create());

    Livewire::test(CandidacyComponent::class, ['job' => Job::factory()->create()])
        ->assertSet('user', $user);

    $this->assertAuthenticatedAs($user);
});

it('should be able to see correct button to guest', function () {
    $job = Job::factory()
        ->actived()
        ->create([
            'content'       => JobContent::Detailable,
            'configuration' => [
                'anonymous' => false,
                'github'    => true,
                'resume'    => true,
            ],
        ]);

    $this->get(route('job.view', $job))
        ->assertSee($job->description)
        ->assertSee(__('Gostou deste anúncio?'));
});

it('should be able to see correct button to auth user', function () {
    $job = Job::factory()
        ->actived()
        ->create([
            'content'       => JobContent::Detailable,
            'configuration' => [
                'anonymous' => false,
                'github'    => true,
                'resume'    => true,
            ],
        ]);

    $this->actingAs(User::factory()->create());

    $this->get(route('job.view', $job))
         ->assertSee(__('CANDIDATE-SE'));
});

it('should be able to dispatch event successfully', function () {
    $this->actingAs($user = User::factory()->create(['phone' => fake()->phoneNumber()]));

    $job = Job::factory()
        ->notification('https://hooks.slack.com/services/T01J2QZJ9QW/B01J2QZJ9QW/1')
        ->create();

    $candidacy = [
        'phone' => fake()->phoneNumber(),
    ];

    Event::fake();

    Livewire::test(CandidacyComponent::class, ['job' => $job, 'user' => $user])
        ->set('candidacy', $candidacy)
        ->set('terms', true)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'success',
            'message' => 'Sucesso!',
            'text'    => 'Candidatura Enviada. Boa sorte!',
        ]);

    Event::assertDispatched(JobCandidacy::class, function ($event) use ($job) {
        return $event->job->id === $job->id;
    });
});

it('should be able to candidacy successfully', function () {
    $this->actingAs($user = User::factory()->create(['phone' => fake()->phoneNumber()]));

    $job = Job::factory()
        ->notification('https://hooks.slack.com/services/T01J2QZJ9QW/B01J2QZJ9QW/1')
        ->create();

    Notification::fake();

    Livewire::test(CandidacyComponent::class, ['job' => $job, 'user' => $user])
        ->set('terms', true)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'success',
            'message' => 'Sucesso!',
            'text'    => 'Candidatura Enviada. Boa sorte!',
        ]);

    $this->assertDatabaseHas('candidacies', [
        'job_id'  => $job->id,
        'user_id' => $user->id,
    ]);

    Notification::assertSentTo(
        new AnonymousNotifiable(),
        CandidacyJobNotification::class,
        function ($notification, $channels, $notifiable) use ($job) {
            return $notifiable->routes[SlackChannel::class] === $job->notification;
        }
    );
});

it('should be able to candidacy and send email notification successfully', function () {
    $this->actingAs($user = User::factory()->create(['phone' => fake()->phoneNumber()]));

    $job = Job::factory()
        ->notification($email = fake()->email())
        ->create();

    Notification::fake();

    Livewire::test(CandidacyComponent::class, ['job' => $job])
        ->set('terms', true)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'success',
            'message' => 'Sucesso!',
            'text'    => 'Candidatura Enviada. Boa sorte!',
        ]);

    $this->assertDatabaseHas('candidacies', [
        'job_id'  => $job->id,
        'user_id' => $user->id,
    ]);

    Notification::assertSentTo(
        new AnonymousNotifiable(),
        CandidacyJobNotification::class,
        function ($notification, $channels, $notifiable) use ($email) {
            return $notifiable->routes['mail'] === $email;
        }
    );
});

it('should be able to candidacy and send resume attached successfully', function () {
    $name = sprintf('%s.pdf', Str::lower(Str::random(20)));
    $copy = rescue(fn () => copy(base_path('tests/Fixtures/test.pdf'), storage_path('app/public/resumes/' . $name)), false);

    if ($copy === false) {
        $this->markTestSkipped('could not copy file');
    }

    $this->actingAs($user = User::factory()->create(['phone' => fake()->phoneNumber(), 'resume' => $name]));

    $job = Job::factory()
        ->notification(fake()->email())
        ->create([
            'configuration' => [
                'github' => true,
                'resume' => true,
            ],
        ]);

    Notification::fake();

    Livewire::test(CandidacyComponent::class, ['job' => $job])
        ->set('terms', true)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'success',
            'message' => 'Sucesso!',
            'text'    => 'Candidatura Enviada. Boa sorte!',
        ]);

    $this->assertDatabaseHas('candidacies', [
        'job_id'  => $job->id,
        'user_id' => $user->id,
    ]);

    Notification::assertSentTo(
        new AnonymousNotifiable(),
        CandidacyJobNotification::class,
        function ($notification, $channels, $notifiable) use ($name) {
            $attachment = $notification->toMail($notifiable)->attachments;

            return expect($attachment)
                ->toBeArray()
                ->and($attachment)
                ->toHaveCount(1)
                ->and($attachment[0]['file'])
                ->toContain($name);
        }
    );
});

it('should not be able to candidacy for a job twice', function () {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->actived()
        ->create();

    Livewire::test(CandidacyComponent::class, ['job' => $job])
        ->set('terms', true)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'success',
            'message' => 'Sucesso!',
            'text'    => 'Candidatura Enviada. Boa sorte!',
        ]);

    $this->assertDatabaseHas('candidacies', [
        'job_id'  => $job->id,
        'user_id' => $user->id,
    ]);

    Livewire::test(CandidacyComponent::class, ['job' => $job])
        ->set('terms', true)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'error',
            'message' => 'Ops!',
            'text'    => 'Você já é um candidato para esta vaga.',
        ]);

    $this->assertDatabaseCount('candidacies', 1);
});

it('should not be able to candidacy for a job created by the same user', function () {
    $this->actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->for($user)
        ->create();

    $candidacy = [
        'phone' => fake()->phoneNumber(),
    ];

    Livewire::test(CandidacyComponent::class, ['job' => $job])
        ->set('candidacy', $candidacy)
        ->set('terms', true)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertDispatchedBrowserEvent('swal:common', [
            'type'    => 'error',
            'message' => 'Ops!',
            'text'    => 'Você não pode se candidatar a esta vaga!',
        ]);

    $this->assertDatabaseMissing('candidacies', [
        'job_id'  => $job->id,
        'user_id' => $user->id,
    ]);
});
