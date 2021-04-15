<?php
declare(strict_types=1);

namespace App\Tests\ObjectMothers\Acme\Users\Application\Requests;


use App\Acme\Users\Application\Requests\CreateUserRequest;
use App\Acme\Users\Domain\Models\User;

final class CreateUserRequestMother
{
    public static function fromUser(User $user): CreateUserRequest
    {
        return new CreateUserRequest(
            $user->getId()->value(),
            $user->getUserName()->value()
        );
    }
}