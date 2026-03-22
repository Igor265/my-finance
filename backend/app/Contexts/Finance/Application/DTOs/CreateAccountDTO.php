<?php

namespace App\Contexts\Finance\Application\DTOs;

class CreateAccountDTO
{
    public function __construct(
        public readonly string $userId,
        public readonly string $name,
        public readonly string $type,
        public readonly float $initialAmount = 0.0,
        public readonly string $currency = 'BRL',
    ) {}
}
