<?php

namespace App\Enums;

use App\Enums\Traits\ToArray;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;

enum SocialiteProvider: string
{
    use ToArray;

    case Google = 'google';
    case GitHub = 'github';

    public function driver(): Provider
    {
        return Socialite::driver($this->value);
    }
}
