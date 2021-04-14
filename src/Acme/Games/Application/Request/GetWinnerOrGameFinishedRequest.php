<?php
declare(strict_types=1);

namespace App\Acme\Games\Application\Request;


final class GetWinnerOrGameFinishedRequest
{
    public function __construct(private string $gameId)
    {
    }

    public function getGameId(): string
    {
        return $this->gameId;
    }
}