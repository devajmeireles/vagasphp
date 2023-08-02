<?php

namespace App\Enums;

use App\Enums\Traits\ToArray;

enum JobStatus: int
{
    use ToArray;

    case Review    = 1;
    case Actived   = 2;
    case Expired   = 3;
    case Completed = 4;

    public function translate(): string
    {
        return __('app.job.status.' . str($this->name)->lower());
    }

    public function badge(): string
    {
        return match ($this) {
            self::Actived   => 'green',
            self::Expired   => 'red',
            self::Review    => 'yellow',
            self::Completed => 'gray',
        };
    }
}
