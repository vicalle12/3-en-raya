<?php
declare(strict_types=1);

namespace App\Tests\ObjectMothers\Acme\Games\Application\Requests;


use App\Acme\Games\Application\Request\StartGameRequest;
use App\Acme\Games\Domain\Models\Game;

final class StartGameRequestMother
{
    public static function fromGame(Game $game): StartGameRequest
    {
        return new StartGameRequest(
            $game->getId()->value(),
            $game->getUser1()->getId()->value(),
            $game->getUser2()->getId()->value()
        );
    }
}