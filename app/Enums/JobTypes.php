<?php

namespace App\Enums;

use App\Enums\Traits\ToArray;

enum JobTypes: int
{
    use ToArray;

    case FreeLance  = 2;
    case Temporary  = 3;
    case Internship = 4;
    case Permanent  = 5;

    public function translate(): string
    {
        return __('app.job.type.' . $this->name);
    }
}
