<?php
declare(strict_types=1);

namespace App\Acme\Games\Application\Request;


final class StartGameRequest
{
    public function __construct(
        private string $gameId,
        private string $userIdOne,
        private string $userIdTwo,
    )
    {
    }

    public function getGameId(): string
    {
        return $this->gameId;
    }

    public function getUserIdOne(): string
    {
        return $this->userIdOne;
    }

    public function getUserIdTwo(): string
    {
        return $this->userIdTwo;
    }
}
