<?php
declare(strict_types=1);

namespace App\Tests\ObjectMothers\Acme\Games\Domain\Entities;


use App\Acme\Games\Domain\Entities\Board;
use App\Acme\Games\Domain\Entities\Game;
use App\Acme\Shared\Domain\Entities\GameId;
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
}