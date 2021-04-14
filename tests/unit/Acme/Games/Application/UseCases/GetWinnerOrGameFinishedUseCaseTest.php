<?php
declare(strict_types=1);

namespace App\Tests\Unit\Acme\Games\Application\UseCases;

use App\Acme\Games\Application\Request\GetWinnerOrGameFinishedRequest;
use App\Acme\Games\Application\UseCases\GetWinnerOrGameFinishedUseCase;
use App\Acme\Games\Domain\Entities\BoardPosition;
use App\Acme\Games\Domain\Entities\UserMovement;
use App\Acme\Games\Domain\Repositories\GameRepository;
use App\Tests\ObjectMothers\Acme\Games\Domain\Entities\GameMother;
use App\Tests\ObjectMothers\Acme\Games\Domain\Entities\UserMother;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class GetWinnerOrGameFinishedUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private GetWinnerOrGameFinishedUseCase $sub;
    private \Prophecy\Prophecy\ObjectProphecy|GameRepository $gameRepository;

    protected function setUp(): void
    {
        $this->gameRepository = $this->prophesize(GameRepository::class);
        $this->sub = new GetWinnerOrGameFinishedUseCase(
            $this->gameRepository->reveal()
        );
    }

    public function testGetWinner(): void
    {
        $winner = UserMother::random();
        $game = GameMother::withWinner($winner);

        $this->gameRepository->findBy($game->getId())
            ->willReturn($game);

        $response = $this->sub->__invoke(new GetWinnerOrGameFinishedRequest(
            $game->getId()->value()
        ));

        self::assertEquals($response->getWinnerId(), $game->getWinner()->getId()->value());
        self::assertTrue($response->isGameFinished());
    }

    public function testGameIsFinishedWithoutWinner(): void
    {
        $game = GameMother::finished();

        $this->gameRepository->findBy($game->getId())
            ->willReturn($game);

        $response = $this->sub->__invoke(new GetWinnerOrGameFinishedRequest(
            $game->getId()->value()
        ));

        self::assertEmpty($response->getWinnerId());
        self::assertTrue($response->isGameFinished());
    }

    public function testGameIsNotFinishedAndNoWinner(): void
    {
        $game = GameMother::random();
        $game->move(new UserMovement($game->getUser1(), BoardPosition::random()));

        $this->gameRepository->findBy($game->getId())
            ->willReturn($game);

        $response = $this->sub->__invoke(new GetWinnerOrGameFinishedRequest(
            $game->getId()->value()
        ));

        self::assertEmpty($response->getWinnerId());
        self::assertFalse($response->isGameFinished());
    }
}
