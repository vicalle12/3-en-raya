<?php
declare(strict_types=1);

namespace App\Acme\Games\Domain\Models;


use App\Acme\Games\Domain\Events\StartGameEvent;
use App\Acme\Games\Domain\Exceptions\UserCantMove;
use App\Acme\Shared\Domain\Models\GameId;
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

    public function move(UserMovement $movement): void
    {
        if (!$this->userCanMove($movement->getUser())) {
            var_dump($this->board->isFull(), $this->board->value);
            throw new UserCantMove($movement->getUser()->getId()->value());
        }

        $this->board->addMovement($movement);
    }

    public function isFinished(): bool
    {
        return !empty($this->getWinner()) || $this->board->isFull();
    }

    public function getWinner(): ?User
    {
        return $this->board->getWinner();
    }

    private function nextMoveUser(): User
    {
        $lastMove = $this->board->lastMove();

        if (!empty($lastMove) && $lastMove->getUser()->equals($this->user1)) {
            return $this->user2;
        }

        return $this->user1;
    }

    private function userIsPlaying(User $user): bool
    {
        return $user->getId()->equals($this->getUser1()->getId()) || $user->getId()->equals($this->getUser2()->getId());
    }

    private function userCanMove(User $user): bool
    {
        return
            $this->userIsPlaying($user) &&
            $this->nextMoveUser()->equals($user) &&
            !$this->isFinished();
    }
}