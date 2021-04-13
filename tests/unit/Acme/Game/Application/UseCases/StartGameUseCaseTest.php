<?php
declare(strict_types=1);

namespace App\Tests\Unit\Acme\Game\Application\UseCases;

use App\Acme\Games\Application\Request\StartGameRequest;
use App\Acme\Games\Application\UseCases\StartGameUseCase;
use App\Acme\Games\Domain\Entities\Game;
use App\Acme\Games\Domain\Events\StartGameEvent;
use App\Acme\Games\Domain\Repositories\GameRepository;
use App\Acme\Games\Domain\Repositories\UserRepository;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Tests\ObjectMothers\Acme\Games\Application\Requests\StartGameRequestMother;
use App\Tests\ObjectMothers\Acme\Games\Domain\Entities\GameMother;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class StartGameUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private StartGameUseCase $sub;
    private \Prophecy\Prophecy\ObjectProphecy|EventBus $eventBus;
    private \Prophecy\Prophecy\ObjectProphecy|GameRepository $gameRepository;
    private \Prophecy\Prophecy\ObjectProphecy|UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = $this->prophesize(UserRepository::class);
        $this->gameRepository = $this->prophesize(GameRepository::class);
        $this->eventBus = $this->prophesize(EventBus::class);
        $this->sub = new StartGameUseCase(
            $this->userRepository->reveal(),
            $this->gameRepository->reveal(),
            $this->eventBus->reveal()
        );
    }

    public function testStartGameSuccessfully(): void
    {
        $game = GameMother::random();

        $this->userRepository->findBy($game->getUser1()->getId())
            ->shouldBeCalledOnce()
            ->willReturn($game->getUser1());

        $this->userRepository->findBy($game->getUser2()->getId())
            ->shouldBeCalledOnce()
            ->willReturn($game->getUser2());

        $this->gameRepository->save(Argument::type(Game::class))
            ->shouldBeCalledOnce();

        $this->eventBus->publish(Argument::type(StartGameEvent::class))
            ->shouldBeCalledOnce();

        $this->sub->__invoke(StartGameRequestMother::fromGame($game));
    }
}
