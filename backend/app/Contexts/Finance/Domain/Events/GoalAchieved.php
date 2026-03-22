<?php

namespace App\Contexts\Finance\Domain\Events;

use App\Contexts\Finance\Domain\ValueObjects\Money;
use App\Contexts\Shared\Domain\DomainEvent;

class GoalAchieved extends DomainEvent
{
    readonly string $goalId;
    readonly string $name;
    readonly Money $targetAmount;

    public function __construct(string $goalId, string $name, Money $targetAmount)
    {
        parent::__construct();
        $this->goalId = $goalId;
        $this->name = $name;
        $this->targetAmount = $targetAmount;
    }

    public function eventName(): string
    {
        return 'finance.goal.achieved';
    }
}
