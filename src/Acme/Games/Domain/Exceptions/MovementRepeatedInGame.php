<?php
declare(strict_types=1);

namespace App\Acme\Games\Domain\Exceptions;


use App\Shared\Domain\DomainError;

final class MovementRepeatedInGame extends DomainError
{
    public function errorCode(): string
    {
        return "GAMES-0004";
    }

    protected function errorMessage(): string
    {
        return "Movement repeated in this game";
    }

}