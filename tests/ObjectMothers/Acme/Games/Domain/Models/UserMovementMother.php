<?php
declare(strict_types=1);

namespace App\Tests\ObjectMothers\Acme\Games\Domain\Models;


use App\Acme\Games\Domain\Models\BoardPosition;
use App\Acme\Games\Domain\Models\UserMovement;

final class UserMovementMother
{
    public static function random(): UserMovement
    {
        return new UserMovement(
            UserMother::random(),
            BoardPosition::random()
        );
    }
    public static function withPosition(BoardPosition $boardPosition): UserMovement
    {
        return new UserMovement(
            UserMother::random(),
            $boardPosition
        );
    }
}