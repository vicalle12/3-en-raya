<?php
declare(strict_types=1);

namespace App\Acme\Games\Domain\Exceptions;


use App\Shared\Domain\DomainError;

final class GameNotFound extends DomainError
{
    public function __construct(private string $id)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return "GAMES-0002";
    }

    protected function errorMessage(): string
    {
        return sprintf("Game with gameId <%s> not found", $this->id);
    }

}