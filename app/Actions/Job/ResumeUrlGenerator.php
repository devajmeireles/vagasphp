<?php

namespace App\Actions\Job;

use App\Models\User;

class ResumeUrlGenerator
{
    public static function execute(User $user): ?string
    {
        if (($resume = $user->resume) === null) {
            return null;
        }

        return url(sprintf('storage/resumes/%s', $resume));
    }
}
