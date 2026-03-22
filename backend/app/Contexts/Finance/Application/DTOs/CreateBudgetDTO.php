<?php

namespace App\Contexts\Finance\Application\DTOs;

class CreateBudgetDTO
{
    public function __construct(
        public readonly string $userId,
        public readonly string $categoryId,
        public readonly float $maximumAmount,
        public readonly int $alertPercentage,
        public readonly string $startDate,
        public readonly string $endDate,
        public readonly string $currency = 'BRL',
    ) {}
}
