<?php

namespace App\Events\Job;

use App\Models\{Job, User};
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobCandidacy
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly Job $job,
        public readonly User|array $user,
    ) {
    }
}
