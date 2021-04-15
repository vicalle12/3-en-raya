<?php
declare(strict_types=1);

namespace App\Acme\Users\Infrastructure\Persistence;


use App\Acme\Shared\Domain\Models\UserId;
use App\Acme\Users\Domain\Models\User;
use App\Acme\Users\Domain\Repositories\UserRepository;

final class MemoryUserRepository implements UserRepository
{
    private array $users = [];

    public function save(User $user): void
    {
        $this->users[$user->getId()->value()] = $user;
    }

    public function findBy(UserId $userId): ?User
    {
        return $this->users[$userId->value()] ?? null;
    }

    public function delete(UserId $userId): void
    {
        if ($this->users[$userId->value()]) unset($this->users[$userId->value()]);
    }
}