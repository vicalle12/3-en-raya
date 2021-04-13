<?php
declare(strict_types=1);

namespace App\Acme\Games\Domain\Entities;


use App\Acme\Shared\Domain\Entities\UserId;

final class User
{
    public function __construct(private UserId $id)
    {
    }

    public function getId(): UserId
    {
        return $this->id;
    }
}