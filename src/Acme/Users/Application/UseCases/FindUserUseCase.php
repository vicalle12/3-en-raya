<?php
declare(strict_types=1);

namespace App\Acme\Users\Application\UseCases;


use App\Acme\Shared\Domain\Models\UserId;
use App\Acme\Users\Application\Responses\UserResponse;
use App\Acme\Users\Domain\Exceptions\UserNotFound;
use App\Acme\Users\Domain\Repositories\UserRepository;

final class FindUserUseCase
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke(string $userId): UserResponse
    {
        $user = $this->userRepository->findBy(new UserId($userId));

        if (empty($user)) {
            throw new UserNotFound($userId);
        }

        return UserResponse::fromUser($user);
    }
}