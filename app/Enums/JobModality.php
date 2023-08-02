<?php

namespace App\Enums;

use App\Enums\Traits\ToArray;

enum JobModality: int
{
    use ToArray;

    case Presential = 1;
    case Remote     = 2;
    case Hybrid     = 3;

    public function translate(): string
    {
        return __('app.job.modality.' . $this->name);
    }
}
