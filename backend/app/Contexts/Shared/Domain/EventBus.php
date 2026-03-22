<?php

namespace App\Contexts\Shared\Domain;

interface EventBus
{
    public function publish(DomainEvent ...$events): void;
}
