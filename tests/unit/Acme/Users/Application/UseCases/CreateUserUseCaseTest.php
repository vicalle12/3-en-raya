<?php
declare(strict_types=1);

namespace App\Tests\Unit\Acme\Users\Application\UseCases;

use App\Acme\Users\Application\Requests\CreateUserRequest;
use App\Acme\Users\Application\UseCases\CreateUserUseCase;
use App\Acme\Users\Domain\Entities\User;
use App\Acme\Users\Domain\Events\CreateUserEvent;
use App\Acme\Users\Domain\Repositories\UserRepository;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Tests\ObjectMothers\Acme\Users\Application\Requests\CreateUserRequestMother;
use App\Tests\ObjectMothers\Acme\Users\Domain\Entities\UserMother;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class CreateUserUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private CreateUserUseCase $sub;
    private \Prophecy\Prophecy\ObjectProphecy|UserRepository $userRepository;
    private \Prophecy\Prophecy\ObjectProphecy|EventBus $eventBus;

    protected function setUp(): void
    {
        $this->userRepository = $this->prophesize(UserRepository::class);
        $this->eventBus = $this->prophesize(EventBus::class);
        $this->sub = new CreateUserUseCase(
            $this->userRepository->reveal(),
            $this->eventBus->reveal()
        );
    }

    public function testCreateUserSuccessfully(): void
    {
        $user = UserMother::random();
        $userRequest = CreateUserRequestMother::fromUser($user);

        $this->userRepository
            ->create(Argument::type(User::class))
            ->shouldBeCalledOnce();

        $this->eventBus->publish(Argument::type(CreateUserEvent::class))
            ->shouldBeCalledOnce();

        $this->sub->__invoke($userRequest);
    }

    public function testCreateUserWrongUserRequest(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $userRequest = new CreateUserRequest(
            "invalid id",
            "valid name"
        );

        $this->sub->__invoke($userRequest);
    }
}
