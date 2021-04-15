<?php


namespace App\Acme\Games\Domain\Repositories;


use App\Acme\Shared\Domain\Models\UserId;
use App\Acme\Games\Domain\Models\User;

interface UserRepository
{
    public function findBy(UserId $userId): ?User;
}