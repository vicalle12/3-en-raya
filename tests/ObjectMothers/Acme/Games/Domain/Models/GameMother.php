<?php
declare(strict_types=1);

namespace App\Tests\ObjectMothers\Acme\Games\Domain\Models;


use App\Acme\Games\Domain\Models\Board;
use App\Acme\Games\Domain\Models\BoardPosition;
use App\Acme\Games\Domain\Models\Game;
use App\Acme\Games\Domain\Models\User;
use App\Acme\Games\Domain\Models\UserMovement;
use App\Acme\Shared\Domain\Models\GameId;
use App\Shared\Domain\ValueObject\Enum;
use Faker\Factory;

final class GameMother
{
    public static function random(): Game
    {
        $faker = Factory::create();
        return new Game(
            new GameId($faker->uuid),
            UserMother::random(),
            UserMother::random(),
            new Board()
        );
    }

    public static function withWinner(?User $winner = null): Game
    {
        if (empty($winner)) $winner = UserMother::random();

        $faker = Factory::create();
        $game = new Game(
            new GameId($faker->uuid),
            $user1 = UserMother::random(),
            $winner,
            new Board()
        );

        $game->move(new UserMovement($user1, new BoardPosition(BoardPosition::ONEONE)));
        $game->move(new UserMovement($winner, new BoardPosition(BoardPosition::ONETWO)));
        $game->move(new UserMovement($user1, new BoardPosition(BoardPosition::ONETHREE)));
        $game->move(new UserMovement($winner, new BoardPosition(BoardPosition::TWOTWO)));
        $game->move(new UserMovement($user1, new BoardPosition(BoardPosition::THREEONE)));
        $game->move(new UserMovement($winner, new BoardPosition(BoardPosition::TWOTHREE)));
        $game->move(new UserMovement($user1, new BoardPosition(BoardPosition::THREETHREE)));
        $game->move(new UserMovement($winner, new BoardPosition(BoardPosition::THREETWO)));

        return $game;
    }

    public static function finished(): Game
    {
        $faker = Factory::create();
        $game = new Game(
            new GameId($faker->uuid),
            $user1 = UserMother::random(),
            $user2 = UserMother::random(),
            new Board()
        );

        $game->move(new UserMovement($user1, new BoardPosition(BoardPosition::ONEONE)));
        $game->move(new UserMovement($user2, new BoardPosition(BoardPosition::ONETWO)));
        $game->move(new UserMovement($user1, new BoardPosition(BoardPosition::ONETHREE)));
        $game->move(new UserMovement($user2, new BoardPosition(BoardPosition::TWOONE)));
        $game->move(new UserMovement($user1, new BoardPosition(BoardPosition::TWOTWO)));
        $game->move(new UserMovement($user2, new BoardPosition(BoardPosition::THREEONE)));
        $game->move(new UserMovement($user1, new BoardPosition(BoardPosition::TWOTHREE)));
        $game->move(new UserMovement($user2, new BoardPosition(BoardPosition::THREETHREE)));
        $game->move(new UserMovement($user1, new BoardPosition(BoardPosition::THREETWO)));

        return $game;
    }
}