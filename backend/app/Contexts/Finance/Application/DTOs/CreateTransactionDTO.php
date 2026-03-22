<?php

namespace App\Contexts\Finance\Application\DTOs;

class CreateTransactionDTO
{
    public function __construct(
        public readonly string $accountId,
        public readonly float $amount,
        public readonly string $type,
        public readonly string $description,
        public readonly string $date,
        public readonly string $currency = 'BRL',
        public readonly ?string $categoryId = null,
    ) {}
}
