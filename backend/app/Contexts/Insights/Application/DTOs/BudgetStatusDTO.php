<?php

namespace App\Contexts\Insights\Application\DTOs;

class BudgetStatusDTO
{
    public function __construct(
        public readonly string $budgetId,
        public readonly string $categoryId,
        public readonly float $maximumAmount,
        public readonly float $spentAmount,
        public readonly float $percentage,
        public readonly string $currency,
        public readonly string $startDate,
        public readonly string $endDate,
    ) {}
}
