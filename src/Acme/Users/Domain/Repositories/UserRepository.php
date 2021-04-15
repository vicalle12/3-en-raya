<?php


namespace App\Acme\Users\Domain\Repositories;


use App\Acme\Shared\Domain\Models\UserId;
use App\Acme\Users\Domain\Models\User;

interface UserRepository
{
    public function save(User $user): void;

    public function findBy(UserId $userId): ?User;

    public function delete(UserId $userId): void;
}