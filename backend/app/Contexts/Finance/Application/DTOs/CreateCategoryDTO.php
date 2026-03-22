<?php

namespace App\Contexts\Finance\Application\DTOs;

class CreateCategoryDTO
{
    public function __construct(
        public readonly string $userId,
        public readonly string $name,
        public readonly string $type,
    ) {}
}
