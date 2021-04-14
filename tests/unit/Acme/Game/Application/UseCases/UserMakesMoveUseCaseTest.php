<?php
declare(strict_types=1);

namespace App\Tests\Unit\Acme\Game\Application\UseCases;

use App\Acme\Games\Application\Request\UserMakesMoveRequest;
use App\Acme\Games\Application\UseCases\UserMakesMoveUseCase;
use App\Acme\Games\Domain\Entities\BoardPosition;
use App\Acme\Games\Domain\Entities\Game;
use App\Acme\Games\Domain\Events\StartGameEvent;
use App\Acme\Games\Domain\Exceptions\GameNotFound;
use App\Acme\Games\Domain\Exceptions\UserNotFound;
use App\Acme\Games\Domain\Repositories\GameRepository;
use App\Acme\Games\Domain\Repositories\UserRepository;
use App\Tests\ObjectMothers\Acme\Games\Application\Requests\StartGameRequestMother;
use App\Tests\ObjectMothers\Acme\Games\Domain\Entities\GameMother;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class UserMakesMoveUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private UserMakesMoveUseCase $sub;
    private \Prophecy\Prophecy\ObjectProphecy|GameRepository $gameRepository;
    private \Prophecy\Prophecy\ObjectProphecy|UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = $this->prophesize(UserRepository::class);
        $this->gameRepository = $this->prophesize(GameRepository::class);
        $this->sub = new UserMakesMoveUseCase(
            $this->userRepository->reveal(),
            $this->gameRepository->reveal(),
        );
    }

    public function testUserMakesButUserNotFound(): void
    {
        self::expectException(UserNotFound::class);

        $game = GameMother::random();

        $this->userRepository->findBy($game->getUser1()->getId())
            ->willReturn(null);

        $this->sub->__invoke(new UserMakesMoveRequest(
            $game->getId()->value(),
            $game->getUser1()->getId()->value(),
            BoardPosition::randomValue()
        ));
    }

    public function testUserMakesButGameNotFound(): void
    {
        self::expectException(GameNotFound::class);

        $game = GameMother::random();

        $this->userRepository->findBy($game->getUser1()->getId())
            ->willReturn($game->getUser1());

        $this->gameRepository->findBy($game->getId())
            ->willReturn(null);

        $this->sub->__invoke(new UserMakesMoveRequest(
            $game->getId()->value(),
            $game->getUser1()->getId()->value(),
            BoardPosition::randomValue()
        ));
    }

    public function testUserNotPlayingThisGame(): void
    {

    }

    public function testUserCanNotMove(): void
    {

    }

    public function testInvalidMovement(): void
    {

    }

    public function testMovementRepeated(): void
    {

    }
}
