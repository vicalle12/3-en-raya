<?php
declare(strict_types=1);

namespace App\Tests\Unit\Acme\Users\Application\UseCases;

use App\Acme\Shared\Domain\Entities\UserId;
use App\Acme\Users\Application\UseCases\DeleteUserUseCase;
use App\Acme\Users\Domain\Exceptions\UserNotFound;
use App\Acme\Users\Domain\Repositories\UserRepository;
use App\Tests\ObjectMothers\Acme\Users\Domain\Entities\UserMother;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class DeleteUserUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private DeleteUserUseCase $sub;
    private \Prophecy\Prophecy\ObjectProphecy|UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = $this->prophesize(UserRepository::class);
        $this->sub = new DeleteUserUseCase(
            $this->userRepository->reveal()
        );
    }

    public function testDeleteUserSuccessfully(): void
    {
        $user = UserMother::random();

        $this->userRepository
            ->findBy(Argument::type(UserId::class))
            ->willReturn($user)
            ->shouldBeCalledOnce();

        $this->userRepository
            ->delete(Argument::type(UserId::class))
            ->shouldBeCalledOnce();

        $this->sub->__invoke($user->getId());

        self::assertTrue(true);
    }

    public function testUserNotFound(): void
    {
        self::expectException(UserNotFound::class);

        $this->userRepository
            ->findBy(Argument::type(UserId::class))
            ->willReturn(null)
            ->shouldBeCalledOnce();

        $faker = Factory::create();

        $this->sub->__invoke(new UserId($faker->uuid));
    }
}
