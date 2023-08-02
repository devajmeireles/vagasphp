<?php

namespace App\Observers;

use App\Mail\Job\{CreateJobMail, DestroyJobMail, EditJobMail, ExpireJobMail};
use App\Models\Job;
use Illuminate\Support\Facades\Mail;

class JobObserver
{
    public function created(Job $job): void
    {
        Mail::to($job->user)->send(new CreateJobMail($job));
    }

    public function updated(Job $job): void
    {
        if ($job->isReview() === false) {
            return;
        }

        Mail::to($job->user)->send(new EditJobMail($job));
    }

    public function deleted(Job $job): void
    {
        Mail::to($job->user)->send(new ExpireJobMail($job));
    }

    public function forceDeleted(Job $job): void
    {
        Mail::to($job->user)->send(new DestroyJobMail($job));
    }
}
