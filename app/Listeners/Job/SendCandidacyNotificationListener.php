<?php

namespace App\Listeners\Job;

use App\Channels\SlackChannel;
use App\Events\Job\JobCandidacy;
use App\Notifications\CandidacyJobNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendCandidacyNotificationListener implements ShouldQueue
{
    public function handle(JobCandidacy $event): void
    {
        $job  = $event->job;
        $user = $event->user;

        $notify = match ($job->notification === null) {
            true  => fn () => null,
            false => fn () => str($job->notification)->contains('hooks.slack.com') ? SlackChannel::class : 'mail',
        };

        if (($channel = $notify()) === null) {
            return;
        }

        Notification::route($channel, $job->notification)->notify(new CandidacyJobNotification($job, $user));
    }

    public function shouldQueue(JobCandidacy $event): bool
    {
        return $event->job->notification !== null;
    }
}
