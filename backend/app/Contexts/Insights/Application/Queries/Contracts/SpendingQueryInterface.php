<?php

namespace App\Contexts\Insights\Application\Queries\Contracts;

use App\Contexts\Insights\Application\DTOs\SpendingItemDTO;

interface SpendingQueryInterface
{
    /** @return SpendingItemDTO[] */
    public function execute(string $userId): array;
}
