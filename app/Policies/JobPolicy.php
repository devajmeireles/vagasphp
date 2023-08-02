<?php

namespace App\Policies;

use App\Enums\JobStatus;
use App\Models\{Job, User};
use Illuminate\Auth\Access\{HandlesAuthorization, Response};

class JobPolicy
{
    use HandlesAuthorization;

    public function view(?User $user, Job $job): Response|bool
    {
        return $job->status === JobStatus::Actived || $user?->is($job->user);
    }

    public function update(?User $user, Job $job): Response|bool
    {
        return $user?->is($job->user)
            ? Response::allow()
            : Response::deny();
    }

    public function candidacy(?User $user, Job $job): Response|bool
    {
        return $job->status === JobStatus::Actived && ($user !== null && !$user?->is($job->user))
            ? Response::allow()
            : Response::deny();
    }

    public function candidates(?User $user, Job $job): Response|bool
    {
        return $user?->is($job->user);
    }

    public function delete(User $user, Job $job): Response|bool
    {
        return $user->is($job->user);
    }

    public function restore(User $user, Job $job): Response|bool
    {
        return $user->is($job->user);
    }

    public function forceDelete(User $user, Job $job): Response|bool
    {
        return $user->is($job->user);
    }
}
