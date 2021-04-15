<?php


namespace App\Acme\Users\Application\UseCases;


use App\Acme\Shared\Domain\Models\UserId;
use App\Acme\Users\Domain\Exceptions\UserNotFound;
use App\Acme\Users\Domain\Repositories\UserRepository;

final class DeleteUserUseCase
{
    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    public function __invoke(UserId $userId): void
    {
        $user = $this->userRepository->findBy($userId);

        if (empty($user)) {
            throw new UserNotFound($userId->value());
        }

        $this->userRepository->delete($userId);
    }
}