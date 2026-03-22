<?php

namespace App\Contexts\Finance\Domain\Services;

use App\Contexts\Finance\Domain\Entities\FinancialGoal;

class GoalProgressCalculator
{
    public function percentage(FinancialGoal $goal): float
    {
        return ($goal->currentAmount->amount / $goal->targetAmount->amount) * 100;
    }
}
