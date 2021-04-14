<?php
declare(strict_types=1);

namespace App\Acme\Games\Application\UseCases;


use App\Acme\Games\Application\Request\UserMakesMoveRequest;
use App\Acme\Games\Domain\Entities\BoardPosition;
use App\Acme\Games\Domain\Entities\UserMovement;
use App\Acme\Games\Domain\Exceptions\GameNotFound;
use App\Acme\Games\Domain\Exceptions\UserNotFound;
use App\Acme\Games\Domain\Repositories\GameRepository;
use App\Acme\Games\Domain\Repositories\UserRepository;
use App\Acme\Shared\Domain\Entities\GameId;
use App\Acme\Shared\Domain\Entities\UserId;

final class UserMakesMoveUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private GameRepository $gameRepository
    )
    {
    }

    public function __invoke(UserMakesMoveRequest $userMakesMoveRequest)
    {
        $user = $this->userRepository->findBy(new UserId($userMakesMoveRequest->getUserId()));

        if (empty($user)) {
            throw new UserNotFound($userMakesMoveRequest->getUserId());
        }

        $game = $this->gameRepository->findBy(new GameId($userMakesMoveRequest->getGameId()));

        if (empty($game)) {
            throw new GameNotFound($userMakesMoveRequest->getUserId());
        }

        $movement = new UserMovement($user, new BoardPosition($userMakesMoveRequest->getPositionKey()));

        $game->move($movement);
    }
}