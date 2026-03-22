<?php

namespace App\Contexts\Finance\Domain\Events;

use App\Contexts\Finance\Domain\ValueObjects\Money;
use App\Contexts\Shared\Domain\DomainEvent;

class BudgetExceeded extends DomainEvent
{
    public readonly string $budgetId;

    public readonly string $categoryId;

    public readonly Money $spent;

    public readonly Money $maximum;

    public function __construct(string $budgetId, string $categoryId, Money $spent, Money $maximum)
    {
        parent::__construct();
        $this->budgetId = $budgetId;
        $this->categoryId = $categoryId;
        $this->spent = $spent;
        $this->maximum = $maximum;
    }

    public function eventName(): string
    {
        return 'finance.budget.exceeded';
    }
}
