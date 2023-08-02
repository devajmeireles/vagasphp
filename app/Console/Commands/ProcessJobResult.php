<?php

namespace App\Console\Commands;

use App\Models\Job;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\{Cache, DB};

class ProcessJobResult extends Command
{
    protected $signature = 'job:process-job-result';

    protected $description = 'Convert all cached visualizations into results to the database.';

    public function handle(): int
    {
        $collect = collect(Cache::pull('job::visualization') ?? []);

        DB::transaction(fn () => $collect->lazy()->each(fn ($value, $key) => Job::query()->find($key)->update(['result' => $value])));

        return self::SUCCESS;
    }
}
