<?php

namespace App\Enums;

use App\Enums\Traits\ToArray;

enum JobSpecification: int
{
    use ToArray;

    case Php         = 1;
    case Laravel     = 2;
    case CodeIgniter = 3;
    case Symfony     = 4;
    case CakePhp     = 5;
    case Zend        = 6;
    case Phalcon     = 7;
    case Slim        = 9;
    case Lumen       = 10;
    case Yii         = 11;
    case WordPress   = 12;

    public function icon(): string
    {
        return str($this->name)->lower();
    }

    public function name(): string
    {
        return str($this->name)->ucfirst();
    }
}
