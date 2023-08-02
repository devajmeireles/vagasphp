<?php

namespace App\Http\Controllers;

use App\Events\Job\JobVisualization;
use App\Models\Job;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /** @throws AuthorizationException */
    public function view(Job $job)
    {
        $this->authorize('view', $job);

        $guest = Auth::guest();

        JobVisualization::dispatchIf($guest, $job);

        if ($guest && $job->redirectable()) {
            return redirect()->away($job->link);
        }

        return view('job.view', compact('job'));
    }
}
