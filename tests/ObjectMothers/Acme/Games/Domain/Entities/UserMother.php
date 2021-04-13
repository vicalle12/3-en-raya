<?php
declare(strict_types=1);

namespace App\Tests\ObjectMothers\Acme\Games\Domain\Entities;


use App\Acme\Games\Domain\Entities\User;
use App\Acme\Shared\Domain\Entities\UserId;
use Faker\Factory;

final class UserMother
{
    public static function random(): User
    {
        $faker = Factory::create();
        return new User(
            new UserId($faker->uuid)
        );
    }
}