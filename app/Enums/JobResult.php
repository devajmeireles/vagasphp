<?php

namespace App\Enums;

use App\Enums\Traits\ToArray;

enum JobResult: int
{
    use ToArray;

    case Reviewed = 1;
    case Expired  = 2;
}
