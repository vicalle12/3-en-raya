<?php
declare(strict_types=1);

namespace App\Acme\Games\Domain\Models;


use App\Acme\Games\Domain\Exceptions\UserCantMove;

final class Board
{
    public array $value;

    // Could be improved by an decision algorithm
    const WINNING_POSITIONS = [
        [[0,0], [0,1], [0,2]],
        [[1,0], [1,1], [1,2]],
        [[2,0], [2,1], [2,2]],
        [[0,0], [1,0], [2,0]],
        [[0,1], [1,1], [2,1]],
        [[0,2], [1,2], [2,2]],
        [[0,0], [1,1], [2,2]],
        [[0,2], [1,1], [2,0]],
    ];

    public function __construct()
    {
        $this->value = [
            [null, null, null],
            [null, null, null],
            [null, null, null]
        ];
    }

    private ?UserMovement $lastMovement = null;

    public function lastMove(): ?UserMovement
    {
        return $this->lastMovement;
    }

    public function addMovement(UserMovement $userMovement): void
    {
        $positions = $this->getMovementPositionKey($userMovement);

        /** @var null|User $position */
        if (!empty($this->value[$positions[0]][$positions[1]])) {
            throw new UserCantMove($userMovement->getUser()->getId()->value());
        }

        $this->value[$positions[0]][$positions[1]] = $userMovement->getUser();
        $this->lastMovement = $userMovement;
    }

    public function isFull(): bool
    {
        foreach ($this->value as $line) {
            foreach ($line as $colum) {
                if (empty($colum)) {
                    return false;
                }
            }
        }
        return true;
    }

    // Considering moving the winning calculation to Game object. ¿Winner of the game or the board, or both?
    // I would ask stakeholders
    public function getWinner(): ?User
    {
        foreach (self::WINNING_POSITIONS as $winningPositions) {
            $position1 = $winningPositions[0];
            $position2 = $winningPositions[1];
            $position3 = $winningPositions[2];

            if (
                !empty($this->value[$position1[0]][$position1[1]]) &&
                $this->value[$position1[0]][$position1[1]]->equals($this->value[$position2[0]][$position2[1]]) &&
                $this->value[$position1[0]][$position1[1]]->equals($this->value[$position3[0]][$position3[1]])
            ) {
                return $this->value[$position1[0]][$position1[1]];
            }
        }
        return null;
    }

    private function getMovementPositionKey(UserMovement $userMovement): array
    {
        return array_map(function ($value) {
            return intval($value)-1;
        }, explode("-", $userMovement->getBoardPosition()->value()));
    }
}