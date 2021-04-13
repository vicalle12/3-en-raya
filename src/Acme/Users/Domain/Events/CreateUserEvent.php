<?php
declare(strict_types=1);

namespace App\Acme\Users\Domain\Events;


use App\Shared\Domain\Bus\Event\DomainEvent;

final class CreateUserEvent extends DomainEvent
{
    public static function eventName(): string
    {
        return 'acme.1.users.created';
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent
    {
        return new self($aggregateId, $eventId, $occurredOn);
    }

    public function toPrimitives(): array
    {
        return [];
    }
}