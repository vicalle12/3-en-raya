<?php
declare(strict_types=1);

namespace App\Acme\Games\Domain\Repositories;


use App\Acme\Games\Domain\Entities\Game;

interface GameRepository
{
    public function save(Game $game): void;
}