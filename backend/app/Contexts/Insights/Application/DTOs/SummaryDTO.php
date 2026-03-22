<?php

namespace App\Contexts\Insights\Application\DTOs;

class SummaryDTO
{
    public function __construct(
        public readonly float $totalBalance,
        public readonly string $currency,
        public readonly int $accountCount,
    ) {}
}
