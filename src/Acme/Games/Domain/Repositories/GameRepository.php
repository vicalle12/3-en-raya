<?php
declare(strict_types=1);

namespace App\Acme\Games\Domain\Repositories;


use App\Acme\Games\Domain\Entities\Game;
use App\Acme\Shared\Domain\Entities\GameId;

interface GameRepository
{
    public function save(Game $game): void;

    public function findBy(GameId $gameId): ?Game;
}