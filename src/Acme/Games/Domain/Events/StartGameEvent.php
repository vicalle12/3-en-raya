<?php
declare(strict_types=1);

namespace App\Acme\Games\Domain\Events;


use App\Shared\Domain\Bus\Event\DomainEvent;

final class StartGameEvent extends DomainEvent
{
    public static function eventName(): string
    {
        return 'acme.1.game.start';
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