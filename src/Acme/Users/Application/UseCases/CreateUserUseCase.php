<?php


namespace App\Acme\Users\Application\UseCases;


use App\Acme\Shared\Domain\Models\UserId;
use App\Acme\Users\Application\Requests\CreateUserRequest;
use App\Acme\Users\Domain\Models\User;
use App\Acme\Users\Domain\Models\UserName;
use App\Acme\Users\Domain\Repositories\UserRepository;
use App\Shared\Domain\Bus\Event\EventBus;

final class CreateUserUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private EventBus $bus
    )
    {
    }

    public function __invoke(CreateUserRequest $createUserRequest): void
    {
        $user = User::create(
            new UserId($createUserRequest->getUuid()),
            new UserName($createUserRequest->getUsername())
        );

        $this->userRepository->save($user);

        $this->bus->publish(...$user->pullDomainEvents());
    }
}