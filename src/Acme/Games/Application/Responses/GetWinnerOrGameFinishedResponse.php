<?php
declare(strict_types=1);

namespace App\Acme\Games\Application\Responses;



final class GetWinnerOrGameFinishedResponse
{
    public function __construct(
        private ?string $winnerId,
        private bool $isGameFinished,
        private ?string $nextPlayerId
    )
    {
    }

    public function getWinnerId(): ?string
    {
        return $this->winnerId;
    }

    public function isGameFinished(): bool
    {
        return $this->isGameFinished;
    }

    public function getNextPlayerId(): ?string
    {
        return $this->nextPlayerId;
    }
}