<?php
declare(strict_types=1);

namespace Apps\TicTacToe\Src\CLI;

use App\Acme\Games\Application\Request\GetWinnerOrGameFinishedRequest;
use App\Acme\Games\Application\Request\StartGameRequest;
use App\Acme\Games\Application\Request\UserMakesMoveRequest;
use App\Acme\Games\Application\Responses\GetWinnerOrGameFinishedResponse;
use App\Acme\Games\Application\UseCases\GetWinnerOrGameFinishedUseCase;
use App\Acme\Games\Application\UseCases\StartGameUseCase;
use App\Acme\Games\Application\UseCases\UserMakesMoveUseCase;
use App\Acme\Games\Domain\Models\BoardPosition;
use App\Acme\Shared\Domain\Models\UserId;
use App\Acme\Users\Application\Requests\CreateUserRequest;
use App\Acme\Users\Application\UseCases\CreateUserUseCase;
use App\Acme\Users\Application\UseCases\DeleteUserUseCase;
use App\Acme\Users\Application\UseCases\FindUserUseCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

final class StartTicTacToeGameCli extends Command
{
    const CREATE_USER = "create user";
    const FIND_USER = "find user";
    const DELETE_USER = "delete user";
    const START_GAME = "start game";
    const MOVE = "move";
    const RESULT = "get result";
    const EXIT = "exit";

    public function __construct(
        private CreateUserUseCase $createUserUseCase,
        private DeleteUserUseCase $deleteUserUseCase,
        private FindUserUseCase $findUserUseCase,
        private StartGameUseCase $startGameUseCase,
        private GetWinnerOrGameFinishedUseCase $getWinnerOrGameFinishedUseCase,
        private UserMakesMoveUseCase $userMakesMoveUseCase
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Started');

        if ($this->startingActions($input, $output))
            return Command::SUCCESS;

        return Command::FAILURE;
    }

    private function startingActions(InputInterface $input, OutputInterface $output): bool
    {
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion('Select Action:', [
            self::CREATE_USER,
            self::FIND_USER,
            self::DELETE_USER,
            self::START_GAME,
            self::EXIT
        ], 6);

        $question->setErrorMessage('Action %s is invalid.');

        $action = $helper->ask($input, $output, $question);
        $output->writeln('You have just selected: ' . $action);

        switch ($action) {
            case self::CREATE_USER:
                $this->createUserAction($input, $output);
                $this->startingActions($input, $output);
                break;
            case self::FIND_USER:
                $this->findUserAction($input, $output);
                $this->startingActions($input, $output);
                break;
            case self::DELETE_USER:
                $this->deleteUserAction($input, $output);
                $this->startingActions($input, $output);
                break;
            case self::START_GAME:
                $this->startGameAction($input, $output);
                $this->startingActions($input, $output);
                break;
            case self::EXIT:
                return true;
        }
        return false;
    }

    private function createUserAction(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelper('question');
        $question = new Question('Write User Name:');

        $name = $helper->ask($input, $output, $question);

        $this->createUserUseCase->__invoke(new CreateUserRequest(
            (string)$id = Uuid::uuid4(),
            $name
        ));

        $output->writeln("User <$name> created with id: $id");
    }

    private function findUserAction(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelper('question');
        $question = new Question('Write User Id from user you want to find:');

        $id = $helper->ask($input, $output, $question);

        $user = $this->findUserUseCase->__invoke($id);

        $output->writeln(sprintf("User <%s> found. Name %s", $user->getId(), $user->getName()));
    }

    private function deleteUserAction(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelper('question');
        $question = new Question('Write User Id from user you want to delete:');

        $id = $helper->ask($input, $output, $question);

        $this->deleteUserUseCase->__invoke(new UserId($id));

        $output->writeln(sprintf("User <%s> deleted.",  $id));
    }

    private function startGameAction(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelper('question');
        $question = new Question('Write User Id from player 1:');

        $id1 = $helper->ask($input, $output, $question);

        $question = new Question('Write User Id from player 2:\n');

        $id2 = $helper->ask($input, $output, $question);

        $this->startGameUseCase->__invoke(new StartGameRequest(
            $gameId = Uuid::uuid4()->toString(),
            $id1,
            $id2
        ));

        $output->writeln(sprintf("Game started, with Id: %s", $gameId));

        $this->gameAction($input, $output, $gameId);
    }

    private function gameAction(InputInterface $input, OutputInterface $output, string $gameId): void
    {
        $game = $this->getWinnerOrGameFinishedUseCase->__invoke(new GetWinnerOrGameFinishedRequest($gameId));
        $this->printGameSituation($game, $output);

        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion('Select Action:', array_merge(array_values(BoardPosition::values()), [
            self::EXIT
        ]), 6);

        $question->setErrorMessage('Action %s is invalid.');

        $action = $helper->ask($input, $output, $question);
        $output->writeln('You have just selected: ' . $action);

        switch ($action) {
            case self::EXIT:
                throw new \Exception("bye. Thanks");
            default:
                $this->userMakesMoveUseCase->__invoke(new UserMakesMoveRequest(
                    $gameId,
                    $game->getNextPlayerId(),
                    $action
                ));
        }

        $this->gameAction($input, $output, $gameId);
    }

    private function printGameSituation(GetWinnerOrGameFinishedResponse $game, OutputInterface $output): void
    {
        if ($game->isGameFinished()) {
            $output->writeln('Game is finished');
        }

        if (empty($game->getWinnerId())) {
            $output->writeln("No winner yet.");
            $output->writeln(sprintf("Next player is: %s", $game->getNextPlayerId()));
        } else {
            $output->writeln(sprintf("Winner is: %s", $game->getWinnerId()));
            throw new \Exception(sprintf("%s is the winner! Bye!", $game->getWinnerId()));
        }
    }
}
