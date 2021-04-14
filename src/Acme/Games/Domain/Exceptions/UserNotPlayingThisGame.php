<?php
declare(strict_types=1);

namespace App\Acme\Games\Domain\Exceptions;


use App\Shared\Domain\DomainError;

final class UserNotPlayingThisGame extends DomainError
{
    public function __construct(private string $userId, private string $gameId)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return "GAMES-0003";
    }

    protected function errorMessage(): string
    {
        return sprintf("User with userId <%s> not playing game <%s>", $this->userId, $this->gameId);
    }

}