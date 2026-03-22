<?php

namespace App\Contexts\Finance\Domain\Entities;

use App\Contexts\Finance\Domain\ValueObjects\BudgetLimit;
use App\Contexts\Finance\Domain\ValueObjects\Period;

class Budget
{
    public readonly string $id;

    public readonly string $userId;

    public readonly string $categoryId;

    public readonly BudgetLimit $limit;

    public readonly Period $period;

    public function __construct(string $id, string $userId, string $categoryId, BudgetLimit $limit, Period $period)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->categoryId = $categoryId;
        $this->limit = $limit;
        $this->period = $period;
    }
}
