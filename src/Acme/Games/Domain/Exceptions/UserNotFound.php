<?php
declare(strict_types=1);

namespace App\Acme\Games\Domain\Exceptions;


use App\Shared\Domain\DomainError;

final class UserNotFound extends DomainError
{
    public function __construct(private string $id)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return "GAMES-0001";
    }

    protected function errorMessage(): string
    {
        return sprintf("User with userId <%s> not found", $this->id);
    }

}