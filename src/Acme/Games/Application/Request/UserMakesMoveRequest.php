<?php
declare(strict_types=1);

namespace App\Acme\Games\Application\Request;


final class UserMakesMoveRequest
{
    public function __construct(
        private string $gameId,
        private string $userId,
        private string $positionKey
    )
    {
    }

    public function getGameId(): string
    {
        return $this->gameId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getPositionKey(): string
    {
        return $this->positionKey;
    }
}