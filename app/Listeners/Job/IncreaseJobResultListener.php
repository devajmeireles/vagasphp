<?php

namespace App\Listeners\Job;

use App\Events\Job\JobVisualization;
use Illuminate\Support\Facades\Cache;

class IncreaseJobResultListener
{
    public function handle(JobVisualization $event): void
    {
        $key     = 'job::visualization';
        $job     = $event->job;
        $collect = collect(Cache::pull($key) ?? []);

        $collect->put($job->id, $collect->get($job->id, $job->result) + 1);

        Cache::rememberForever($key, fn () => $collect->toArray());
    }
}
