<?php
declare(strict_types=1);

namespace App\Acme\Games\Application\UseCases;


use App\Acme\Games\Application\Request\StartGameRequest;
use App\Acme\Games\Domain\Entities\Game;
use App\Acme\Games\Domain\Repositories\GameRepository;
use App\Acme\Games\Domain\Repositories\UserRepository;
use App\Acme\Shared\Domain\Entities\GameId;
use App\Acme\Shared\Domain\Entities\UserId;
use App\Shared\Domain\Bus\Event\EventBus;

final class StartGameUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private GameRepository $gameRepository,
        private EventBus $eventBus
    )
    {
    }

    public function __invoke(StartGameRequest $gameRequest): void
    {
        $randomPosition = (bool)rand(0,1);
        $first = $randomPosition ? $gameRequest->getUserIdOne() : $gameRequest->getUserIdTwo();
        $second = (!$randomPosition) ? $gameRequest->getUserIdOne() : $gameRequest->getUserIdTwo();

        $user1 = $this->userRepository->findBy(new UserId($first));
        $user2 = $this->userRepository->findBy(new UserId($second));

        $game = Game::create(
            new GameId($gameRequest->getGameId()),
            $user1,
            $user2
        );

        $this->gameRepository->save($game);

        $this->eventBus->publish(...$game->pullDomainEvents());
    }
}