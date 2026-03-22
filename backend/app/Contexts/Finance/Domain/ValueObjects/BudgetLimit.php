<?php

namespace App\Contexts\Finance\Domain\ValueObjects;

class BudgetLimit
{
    public readonly Money $maximum;

    public readonly int $alertPercentage;

    public function __construct(Money $maximum, int $alertPercentage)
    {
        if ($alertPercentage < 1 || $alertPercentage > 100) {
            throw new \InvalidArgumentException('Alert percentage must be between 1 and 100');
        }
        $this->maximum = $maximum;
        $this->alertPercentage = $alertPercentage;
    }

    public function alertThreshold(): Money
    {
        $amount = (int) round($this->maximum->amount * ($this->alertPercentage / 100));

        return new Money($amount, $this->maximum->currency);
    }
}
