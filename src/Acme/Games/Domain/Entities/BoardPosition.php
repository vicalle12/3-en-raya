<?php
declare(strict_types=1);

namespace App\Acme\Games\Domain\Entities;


use App\Acme\Games\Domain\Exceptions\InvalidboardPosition;
use App\Shared\Domain\ValueObject\Enum;

final class BoardPosition extends Enum
{
    const ONEONE = "1-1";
    const ONETWO = "1-2";
    const ONETHREE = "1-3";

    const TWOONE = "2-1";
    const TWOTWO = "2-2";
    const TWOTHREE = "2-3";

    const THREEONE = "3-1";
    const THREETWO = "3-2";
    const THREETHREE = "3-3";


    protected function throwExceptionForInvalidValue($value): void
    {
        throw new InvalidboardPosition();
    }
}