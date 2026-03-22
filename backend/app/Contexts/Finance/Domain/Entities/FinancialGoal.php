<?php

namespace App\Contexts\Finance\Domain\Entities;

use App\Contexts\Finance\Domain\ValueObjects\Money;

class FinancialGoal
{
    readonly string $id;
    readonly string $userId;
    readonly string $name;
    readonly Money $targetAmount;
    readonly Money $currentAmount;
    readonly \DateTimeImmutable $deadline;

    public function __construct(string $id, string $userId, string $name, Money $targetAmount, Money $currentAmount, \DateTimeImmutable $deadline)
    {
        if ($targetAmount->amount <= 0) {
            throw new \InvalidArgumentException('Target amount must be greater than to zero');
        }
        if ($currentAmount->amount < 0) {
            throw new \InvalidArgumentException('Current amount must be greater than or equal to zero');
        }
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->targetAmount = $targetAmount;
        $this->currentAmount = $currentAmount;
        $this->deadline = $deadline;
    }
}
