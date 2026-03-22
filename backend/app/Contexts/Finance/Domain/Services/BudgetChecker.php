<?php

namespace App\Contexts\Finance\Domain\Services;

use App\Contexts\Finance\Domain\Entities\Budget;
use App\Contexts\Finance\Domain\ValueObjects\Money;

class BudgetChecker
{
    public function isExceeded(Budget $budget, Money $spent): bool
    {
        return $spent->amount > $budget->limit->maximum->amount;
    }
}
