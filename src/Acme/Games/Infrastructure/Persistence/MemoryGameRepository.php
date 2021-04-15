<?php
declare(strict_types=1);

namespace App\Acme\Games\Infrastructure\Persistence;


use App\Acme\Games\Domain\Models\Game;
use App\Acme\Games\Domain\Repositories\GameRepository;
use App\Acme\Shared\Domain\Models\GameId;

final class MemoryGameRepository implements GameRepository
{
    private array $games = [];
    public function save(Game $game): void
    {
        $this->games[$game->getId()->value()] = $game;
    }

    public function findBy(GameId $gameId): ?Game
    {
        return $this->games[$gameId->value()] ?? null;
    }
}
