<?php


namespace App\Acme\Users\Application\Requests;


final class CreateUserRequest
{
    public function __construct(
        private string $uuid,
        private string $username
    )
    {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}