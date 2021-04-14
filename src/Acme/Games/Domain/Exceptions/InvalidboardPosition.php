<?php
declare(strict_types=1);

namespace App\Acme\Games\Domain\Exceptions;


use App\Shared\Domain\DomainError;

final class InvalidboardPosition extends DomainError
{
    public function errorCode(): string
    {
        return "BOARD-0001";
    }

    protected function errorMessage(): string
    {
        return "Invalid board position";
    }

}