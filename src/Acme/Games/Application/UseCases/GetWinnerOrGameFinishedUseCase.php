<?php
declare(strict_types=1);

namespace App\Acme\Games\Application\UseCases;


use App\Acme\Games\Application\Request\GetWinnerOrGameFinishedRequest;
use App\Acme\Games\Application\Responses\GetWinnerOrGameFinishedResponse;
use App\Acme\Games\Domain\Repositories\GameRepository;
use App\Acme\Shared\Domain\Entities\GameId;

final class GetWinnerOrGameFinishedUseCase
{
    public function __construct(private GameRepository $gameRepository)
    {
    }

    public function __invoke(GetWinnerOrGameFinishedRequest $request): GetWinnerOrGameFinishedResponse
    {
        $game = $this->gameRepository->findBy(new GameId($request->getGameId()));

        return new GetWinnerOrGameFinishedResponse(
            $game->getWinner() ? $game->getWinner()->getId()->value() : null,
            $game->isFinished()
        );
    }
}