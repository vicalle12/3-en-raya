<?php


namespace App\Acme\Users\Domain\Repositories;


use App\Acme\Users\Domain\Entities\User;

interface UserRepository
{
    public function create(User $user): void;
}