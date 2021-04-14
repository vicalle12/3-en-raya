<?php
declare(strict_types=1);

namespace App\Acme\Games\Domain\Entities;


final class UserMovement
{
    public function __construct(
        private User $user,
        private BoardPosition $boardPosition
    )
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getBoardPosition(): BoardPosition
    {
        return $this->boardPosition;
    }
}