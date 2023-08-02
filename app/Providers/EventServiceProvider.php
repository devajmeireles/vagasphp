<?php

namespace App\Providers;

use App\Events\{Job\JobCandidacy, Job\JobVisualization};
use App\Listeners\{Job\IncreaseJobResultListener, Job\SendCandidacyNotificationListener};
use App\Models\Job;
use App\Observers\JobObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        JobVisualization::class => [
            IncreaseJobResultListener::class,
        ],
        JobCandidacy::class => [
            SendCandidacyNotificationListener::class,
        ],
    ];

    public function boot(): void
    {
        Job::observe(JobObserver::class);
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
