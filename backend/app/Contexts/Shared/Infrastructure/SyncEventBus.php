<?php

namespace App\Contexts\Shared\Infrastructure;

use App\Contexts\Shared\Domain\DomainEvent;
use App\Contexts\Shared\Domain\EventBus;

class SyncEventBus implements EventBus
{
    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            event($event);
        }
    }
}
