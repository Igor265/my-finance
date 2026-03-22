<?php

namespace App\Contexts\Finance\Application\DTOs;

class CreateFinancialGoalDTO
{
    public function __construct(
        public readonly string $userId,
        public readonly string $name,
        public readonly float $targetAmount,
        public readonly string $deadline,
        public readonly string $currency = 'BRL',
    ) {}
}
