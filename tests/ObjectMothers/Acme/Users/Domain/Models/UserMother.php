<?php
declare(strict_types=1);

namespace App\Tests\ObjectMothers\Acme\Users\Domain\Models;


use App\Acme\Shared\Domain\Models\UserId;
use App\Acme\Users\Domain\Models\OtherData;
use App\Acme\Users\Domain\Models\User;
use App\Acme\Users\Domain\Models\UserName;
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