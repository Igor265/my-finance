<?php

namespace App\Contexts\Insights\Application\DTOs;

class SpendingItemDTO
{
    public function __construct(
        public readonly ?string $categoryId,
        public readonly float $amount,
        public readonly string $currency,
        public readonly int $transactionCount,
    ) {}
}
