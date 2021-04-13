<?php


namespace App\Acme\Games\Domain\Repositories;


use App\Acme\Shared\Domain\Entities\UserId;
use App\Acme\Games\Domain\Entities\User;

interface UserRepository
{
    public function findBy(UserId $userId): ?User;
}