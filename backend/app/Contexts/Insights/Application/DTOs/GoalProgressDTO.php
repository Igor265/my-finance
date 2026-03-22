<?php

namespace App\Contexts\Insights\Application\DTOs;

class GoalProgressDTO
{
    public function __construct(
        public readonly string $goalId,
        public readonly string $name,
        public readonly float $targetAmount,
        public readonly float $currentAmount,
        public readonly float $percentage,
        public readonly string $currency,
        public readonly string $deadline,
    ) {}
}
