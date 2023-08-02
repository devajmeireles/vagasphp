<?php

namespace App\Enums;

use App\Enums\Traits\ToArray;

enum JobContent: int
{
    use ToArray;

    case Redirectable = 1;
    case Detailable   = 2;

    public static function resolve(string $value): self
    {
        return match ($value) {
            'redirectable' => self::Redirectable,
            'detailable'   => self::Detailable,
        };
    }
}
