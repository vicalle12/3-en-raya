<?php


namespace App\Acme\Users\Domain\Models;


use App\Acme\Shared\Domain\Models\UserId;
use App\Acme\Users\Domain\Events\CreateUserEvent;
use App\Shared\Domain\Aggregate\AggregateRoot;

final class User extends AggregateRoot
{
    public function __construct(
        private UserId $id,
        private UserName $userName,
        private OtherData $otherData
    ) {
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getUserName(): UserName
    {
        return $this->userName;
    }

    public function getOtherData(): OtherData
    {
        return $this->otherData;
    }

    public static function create(
        UserId $id,
        UserName $userName,
        OtherData $otherData = null
    ): self
    {
        $user = new self(
            $id,
            $userName,
            $otherData ?? new OtherData()
        );

        $user->record(new CreateUserEvent($id->value()));

        return $user;
    }

}