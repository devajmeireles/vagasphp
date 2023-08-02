<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ShortenedrUrlController extends Controller
{
    public function __invoke(string $shortened, Request $request)
    {
        /** @var Job|null $job */
        $job = Cache::rememberForever(sprintf('job::shortened::%s', $shortened), function () use ($shortened) {
            return Job::query()
                ->firstWhere('link', '=', $shortened);
        });

        abort_if($job === null, 404);

        return redirect(route('job.view', $job));
    }
}
