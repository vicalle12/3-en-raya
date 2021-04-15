<?php
declare(strict_types=1);

namespace App\Acme\Users\Application\Responses;


use App\Acme\Users\Domain\Models\User;

final class UserResponse
{
    public function __construct(private string $id, private string $name)
    {
    }

    public static function fromUser(User $user): self
    {
        return new self($user->getId()->value(), $user->getUserName()->value());
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}