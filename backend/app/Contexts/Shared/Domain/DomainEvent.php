<?php

namespace App\Contexts\Shared\Domain;

abstract class DomainEvent
{
    private \DateTimeImmutable $occurredAt;

    public function __construct()
    {
        $this->occurredAt = new \DateTimeImmutable;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }

    abstract public function eventName(): string;
}
