<?php
declare(strict_types=1);

namespace App\Tests\Unit\Acme\Games\Domain\Entities;

use App\Acme\Games\Domain\Entities\Board;
use App\Acme\Games\Domain\Entities\BoardPosition;
use App\Acme\Games\Domain\Entities\Game;
use App\Acme\Games\Domain\Entities\User;
use App\Acme\Games\Domain\Entities\UserMovement;
use App\Acme\Games\Domain\Exceptions\UserCantMove;
use App\Acme\Shared\Domain\Entities\GameId;
use App\Tests\ObjectMothers\Acme\Games\Domain\Entities\UserMother;
use App\Tests\ObjectMothers\Acme\Games\Domain\Entities\UserMovementMother;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function testCreateGameSuccessfully(): void
    {
        $faker = Factory::create();
        $user1 = UserMother::random();
        $user2 = UserMother::random();

        $game = new Game(
            new GameId($faker->uuid),
            $user1,
            $user2,
            new Board()
        );

        self::assertInstanceOf(Game::class, $game);
    }

    /** @dataProvider movementWithoutWinner */
    public function testGameHasNoWinner(User $user1, User $user2, array $movements): void
    {
        $faker = Factory::create();

        $game = new Game(
            new GameId($faker->uuid),
            $user1,
            $user2,
            new Board()
        );

        foreach ($movements as $movement) {
            $game->move($movement);
        }

        self::assertEmpty($game->getWinner());
    }

    /** @dataProvider movementWithWinner */
    public function testGameHasWinnerAndIsFinished(User $user1, User $user2, array $movements, User $winner): void
    {
        $faker = Factory::create();

        $game = new Game(
            new GameId($faker->uuid),
            $user1,
            $user2,
            new Board()
        );

        foreach ($movements as $movement) {
            $game->move($movement);
        }

        self::assertEquals($winner, $game->getWinner());
        self::assertTrue($game->isFinished());
    }

    /** @dataProvider movementFinishBoardWithoutWinner */
    public function testGameIsFinishedWithoutWinner(User $user1, User $user2, array $movements): void
    {
        $faker = Factory::create();

        $game = new Game(
            new GameId($faker->uuid),
            $user1,
            $user2,
            new Board()
        );

        foreach ($movements as $movement) {
            $game->move($movement);
        }

        self::assertEmpty($game->getWinner());
        self::assertTrue($game->isFinished());
    }

    public function testUserCannotMoveCauseIsNotInTheGame(): void
    {
        self::expectException(UserCantMove::class);

        $faker = Factory::create();

        $user1 = UserMother::random();
        $user2 = UserMother::random();
        $game = new Game(new GameId($faker->uuid), $user1, $user2, new Board());

        $game->move(UserMovementMother::random());
    }

    public function testUserCannotMoveCauseSpotAlreadyFulfilled(): void
    {
        $this->expectException(UserCantMove::class);

        $faker = Factory::create();

        $user1 = UserMother::random();
        $user2 = UserMother::random();
        $game = new Game(new GameId($faker->uuid), $user1, $user2, new Board());

        $position = BoardPosition::random();
        $game->move(new UserMovement($user1, $position));
        $game->move(new UserMovement($user2, $position));
    }

    public function movementWithoutWinner(): array
    {
        $user1 = UserMother::random();
        $user2 = UserMother::random();
        return [
            [$user1, $user2, []],
            [$user1, $user2, [
                new UserMovement($user1, BoardPosition::random()),
            ]],
            [$user1, $user2, [
                new UserMovement($user1, new BoardPosition(BoardPosition::ONEONE)),
                new UserMovement($user2, new BoardPosition(BoardPosition::ONETWO)),
                new UserMovement($user1, new BoardPosition(BoardPosition::ONETHREE)),
            ]],
        ];
    }


    public function movementWithWinner(): array
    {
        $user1 = UserMother::random();
        $user2 = UserMother::random();
        return [
            [$user1, $user2, [
                new UserMovement($user1, new BoardPosition(BoardPosition::ONEONE)),
                new UserMovement($user2, new BoardPosition(BoardPosition::THREETHREE)),
                new UserMovement($user1, new BoardPosition(BoardPosition::ONETWO)),
                new UserMovement($user2, new BoardPosition(BoardPosition::THREETWO)),
                new UserMovement($user1, new BoardPosition(BoardPosition::ONETHREE)),
            ], $user1],
            [$user1, $user2, [
                new UserMovement($user1, new BoardPosition(BoardPosition::ONEONE)),
                new UserMovement($user2, new BoardPosition(BoardPosition::ONETWO)),
                new UserMovement($user1, new BoardPosition(BoardPosition::ONETHREE)),
                new UserMovement($user2, new BoardPosition(BoardPosition::TWOTWO)),
                new UserMovement($user1, new BoardPosition(BoardPosition::THREEONE)),
                new UserMovement($user2, new BoardPosition(BoardPosition::TWOTHREE)),
                new UserMovement($user1, new BoardPosition(BoardPosition::THREETHREE)),
                new UserMovement($user2, new BoardPosition(BoardPosition::THREETWO)),
            ], $user2],
        ];
    }

    public function movementFinishBoardWithoutWinner(): array
    {
        $user1 = UserMother::random();
        $user2 = UserMother::random();
        return [
            [$user1, $user2, [
                new UserMovement($user1, new BoardPosition(BoardPosition::ONEONE)),
                new UserMovement($user2, new BoardPosition(BoardPosition::ONETWO)),
                new UserMovement($user1, new BoardPosition(BoardPosition::ONETHREE)),
                new UserMovement($user2, new BoardPosition(BoardPosition::TWOONE)),
                new UserMovement($user1, new BoardPosition(BoardPosition::TWOTWO)),
                new UserMovement($user2, new BoardPosition(BoardPosition::THREEONE)),
                new UserMovement($user1, new BoardPosition(BoardPosition::TWOTHREE)),
                new UserMovement($user2, new BoardPosition(BoardPosition::THREETHREE)),
                new UserMovement($user1, new BoardPosition(BoardPosition::THREETWO)),
            ]],
        ];
    }
}
