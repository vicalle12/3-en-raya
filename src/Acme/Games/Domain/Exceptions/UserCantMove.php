<?php
declare(strict_types=1);

namespace App\Acme\Games\Domain\Exceptions;


use App\Shared\Domain\DomainError;

final class UserCantMove extends DomainError
{
    public function __construct(private string $userId)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return "GAMES-0004";
    }

    protected function errorMessage(): string
    {
        return sprintf("User <%s> can not move", $this->userId);
    }

}