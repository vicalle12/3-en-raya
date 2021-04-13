<?php
declare(strict_types=1);

namespace App\Acme\Games\Domain\Entities;


use App\Acme\Games\Domain\Events\StartGameEvent;
use App\Acme\Shared\Domain\Entities\GameId;
use App\Shared\Domain\Aggregate\AggregateRoot;

final class Game extends AggregateRoot
{
    public function __construct(
        private GameId $id,
        private User $user1,
        private User $user2,
        private Board $board
    ) {
    }

    public static function create(
        GameId $gameId,
        User $user1,
        User $user2
    ): self
    {
        $game = new self(
            $gameId,
            $user1,
            $user2,
            new Board()
        );

        $game->record(new StartGameEvent($gameId->value()));

        return $game;
    }

    public function getId(): GameId
    {
        return $this->id;
    }

    public function getUser1(): User
    {
        return $this->user1;
    }

    public function getUser2(): User
    {
        return $this->user2;
    }

    public function getBoard(): Board
    {
        return $this->board;
    }
}