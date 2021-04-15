<?php
declare(strict_types=1);

namespace App\Acme\Games\Infrastructure\Persistence;


use App\Acme\Games\Domain\Models\User;
use App\Acme\Games\Domain\Repositories\UserRepository;
use App\Acme\Shared\Domain\Models\UserId;
use App\Acme\Users\Application\UseCases\FindUserUseCase;

final class BusUserRepository implements UserRepository
{
    public function __construct(private FindUserUseCase $findUserUseCase)
    {
    }

    public function findBy(UserId $userId): ?User
    {
        $user = $this->findUserUseCase->__invoke($userId->value());

        return $user ? new User(new UserId($user->getId())) : null;
    }
}
