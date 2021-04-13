<?php
declare(strict_types=1);

namespace App\Tests\ObjectMothers\Acme\Users\Domain\Entities;


use App\Acme\Shared\Domain\Entities\UserId;
use App\Acme\Users\Domain\Entities\OtherData;
use App\Acme\Users\Domain\Entities\User;
use App\Acme\Users\Domain\Entities\UserName;
use Faker\Factory;


final class UserMother
{
    public static function random(): User
    {
        $faker = Factory::create();

        return new User(
            new UserId($faker->uuid),
            new UserName($faker->asciify("user name ****")),
            new OtherData()
        );
    }
}