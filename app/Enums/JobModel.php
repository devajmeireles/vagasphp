<?php

namespace App\Enums;

use App\Enums\Traits\ToArray;

enum JobModel: int
{
    use ToArray;

    case Clt      = 1;
    case Contract = 2;

    public function translate(): string
    {
        return __('app.job.model.' . $this->name);
    }
}
