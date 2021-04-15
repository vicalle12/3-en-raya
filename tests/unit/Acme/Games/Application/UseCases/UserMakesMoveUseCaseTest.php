<?php
declare(strict_types=1);

namespace App\Tests\Unit\Acme\Games\Application\UseCases;

use App\Acme\Games\Application\Request\UserMakesMoveRequest;
use App\Acme\Games\Application\UseCases\UserMakesMoveUseCase;
use App\Acme\Games\Domain\Models\BoardPosition;
use App\Acme\Games\Domain\Models\UserMovement;
use App\Acme\Games\Domain\Exceptions\GameNotFound;
use App\Acme\Games\Domain\Exceptions\UserCantMove;
use App\Acme\Games\Domain\Exceptions\UserNotFound;
use App\Acme\Games\Domain\Repositories\GameRepository;
use App\Acme\Games\Domain\Repositories\UserRepository;
use App\Shared\Domain\ValueObject\Enum;
use App\Tests\ObjectMothers\Acme\Games\Domain\Models\GameMother;
use App\Tests\ObjectMothers\Acme\Games\Domain\Models\UserMother;
use PHPUnit\Framework\TestCase;
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
        self::expectException(UserCantMove::class);

        $game = GameMother::random();
        $user = UserMother::random();
        $this->userRepository->findBy($user->getId())
            ->willReturn($user);

        $this->gameRepository->findBy($game->getId())
            ->willReturn($game);

        $this->sub->__invoke(new UserMakesMoveRequest(
            $game->getId()->value(),
            $user->getId()->value(),
            BoardPosition::randomValue()
        ));
    }

    public function testUserCanNotMove(): void
    {
        self::expectException(UserCantMove::class);

        $game = GameMother::random();
        $this->userRepository->findBy($game->getUser2()->getId())
            ->willReturn($game->getUser2());

        $this->gameRepository->findBy($game->getId())
            ->willReturn($game);

        $this->sub->__invoke(new UserMakesMoveRequest(
            $game->getId()->value(),
            $game->getUser2()->getId()->value(),
            BoardPosition::randomValue()
        ));
    }

    public function testIsNotUserTurn(): void
    {
        self::expectException(UserCantMove::class);

        $game = GameMother::random();
        $position = new BoardPosition(BoardPosition::ONEONE);
        $userMovement = new UserMovement($game->getUser1(), $position);
        $game->move($userMovement);

        $this->userRepository->findBy($game->getUser1()->getId())
            ->willReturn($game->getUser1());

        $this->gameRepository->findBy($game->getId())
            ->willReturn($game);

        $this->sub->__invoke(new UserMakesMoveRequest(
            $game->getId()->value(),
            $game->getUser1()->getId()->value(),
            BoardPosition::ONETWO
        ));
    }

    public function testMovementRepeated(): void
    {
        self::expectException(UserCantMove::class);

        $game = GameMother::random();
        $position = BoardPosition::random();
        $userMovement = new UserMovement($game->getUser1(), $position);
        $game->move($userMovement);

        $this->userRepository->findBy($game->getUser2()->getId())
            ->willReturn($game->getUser2());

        $this->gameRepository->findBy($game->getId())
            ->willReturn($game);

        $this->sub->__invoke(new UserMakesMoveRequest(
            $game->getId()->value(),
            $game->getUser2()->getId()->value(),
            $position->value()
        ));
    }

    public function testUserIsFollowingTurn(): void
    {
        $game = GameMother::random();
        $position = new BoardPosition(BoardPosition::ONEONE);
        $userMovement = new UserMovement($game->getUser1(), $position);
        $game->move($userMovement);

        $this->userRepository->findBy($game->getUser2()->getId())
            ->willReturn($game->getUser2());

        $this->gameRepository->findBy($game->getId())
            ->willReturn($game);

        $this->sub->__invoke(new UserMakesMoveRequest(
            $game->getId()->value(),
            $game->getUser2()->getId()->value(),
            BoardPosition::ONETWO
        ));

        self::assertTrue(true);
    }
}
