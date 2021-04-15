<?php
declare(strict_types=1);

namespace App\Tests\Unit\Acme\Games\Domain\Models;

use App\Acme\Games\Domain\Models\Board;
use App\Acme\Games\Domain\Models\BoardPosition;
use App\Acme\Games\Domain\Models\UserMovement;
use App\Acme\Games\Domain\Exceptions\UserCantMove;
use App\Shared\Domain\ValueObject\Enum;
use App\Tests\ObjectMothers\Acme\Games\Domain\Models\UserMother;
use App\Tests\ObjectMothers\Acme\Games\Domain\Models\UserMovementMother;
use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase
{
    /** @dataProvider winningPositions */
    public function testBoardWinner($position1, $position2, $position3): void
    {
        $user = UserMother::random();

        $movement1 = new UserMovement($user, new BoardPosition($position1));
        $movement2 = new UserMovement($user, new BoardPosition($position2));
        $movement3 = new UserMovement($user, new BoardPosition($position3));

        $board = new Board();

        $board->addMovement($movement1);
        $board->addMovement($movement2);
        $board->addMovement($movement3);

        self::assertEquals($user, $board->getWinner());
    }

    /** @dataProvider randomUserMovements */
    public function testBoardLastMovement($movements): void
    {
        $lastMovement = null;
        $board = new Board();
        foreach ((array)$movements as $movement) {
            /** @var UserMovement $movement */
            $board->addMovement($movement);
            $lastMovement = $movement;
        }

        self::assertEquals($lastMovement, $board->lastMove());
    }

    public function testBoardIsFull(): void
    {
        $values = BoardPosition::values();
        $user = UserMother::random();
        $board = new Board();
        foreach ($values as $value) {
            $board->addMovement(new UserMovement($user, new BoardPosition($value)));
        }

        self::assertTrue($board->isFull());
    }

    public function testBoardIsNotFull(): void
    {
        $board = new Board();
        $board->addMovement(UserMovementMother::random());
        self::assertFalse($board->isFull());
    }

    public function testUserCantMoveAlreadyMovedThatPosition(): void
    {
        $this->expectException(UserCantMove::class);

        $movement = UserMovementMother::random();
        $board = new Board();
        $board->addMovement($movement);
        $board->addMovement($movement);
    }
    public function testUserCantMoveOtherUserAlreadyMovedThatPosition(): void
    {
        $this->expectException(UserCantMove::class);

        $movement = UserMovementMother::random();
        $board = new Board();
        $board->addMovement($movement);
        $board->addMovement(UserMovementMother::withPosition($movement->getBoardPosition()));
    }

    public function winningPositions(): array
    {
        return [
            [BoardPosition::ONEONE, BoardPosition::ONETWO, BoardPosition::ONETHREE],
            [BoardPosition::TWOONE, BoardPosition::TWOTWO, BoardPosition::TWOTHREE],
            [BoardPosition::THREEONE, BoardPosition::THREETWO, BoardPosition::THREETHREE],
            [BoardPosition::ONEONE, BoardPosition::TWOONE, BoardPosition::THREEONE],
            [BoardPosition::ONETWO, BoardPosition::TWOTWO, BoardPosition::THREETWO],
            [BoardPosition::ONETHREE, BoardPosition::TWOTHREE, BoardPosition::THREETHREE],
            [BoardPosition::ONEONE, BoardPosition::TWOTWO, BoardPosition::THREETHREE],
            [BoardPosition::ONETHREE, BoardPosition::TWOTWO, BoardPosition::THREEONE],
        ];
    }

    public function randomUserMovements(): array
    {
        return [
            [null],
            [[UserMovementMother::random()]],
            [[
                UserMovementMother::withPosition(new BoardPosition(BoardPosition::ONEONE)),
                UserMovementMother::withPosition(new BoardPosition(BoardPosition::ONETWO)),
            ]],
        ];
    }
}
