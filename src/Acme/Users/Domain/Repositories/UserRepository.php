<?php


namespace App\Acme\Users\Domain\Repositories;


use App\Acme\Shared\Domain\Entities\UserId;
use App\Acme\Users\Domain\Entities\User;

interface UserRepository
{
    public function save(User $user): void;

    public function findBy(UserId $userId): ?User;

    public function delete(UserId $userId): void;
}