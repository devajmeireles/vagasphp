<?php

namespace App\Actions\Job;

use App\Models\User;

class GithubUrlGenerator
{
    public static function execute(User $user): ?string
    {
        if (($github = $user->username) === null) {
            return null;
        }

        return sprintf('https://github.com/%s', $github);
    }
}
