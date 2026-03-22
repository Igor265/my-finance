<?php

namespace App\Contexts\Insights\Application\Queries\Contracts;

use App\Contexts\Insights\Application\DTOs\BudgetStatusDTO;

interface BudgetsStatusQueryInterface
{
    /** @return BudgetStatusDTO[] */
    public function execute(string $userId): array;
}
