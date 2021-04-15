<?php
declare(strict_types=1);

namespace App\Tests\ObjectMothers\Acme\Games\Domain\Models;


use App\Acme\Games\Domain\Models\User;
use App\Acme\Shared\Domain\Models\UserId;
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