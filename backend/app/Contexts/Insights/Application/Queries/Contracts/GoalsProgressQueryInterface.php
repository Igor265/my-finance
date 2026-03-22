<?php

namespace App\Contexts\Insights\Application\Queries\Contracts;

use App\Contexts\Insights\Application\DTOs\GoalProgressDTO;

interface GoalsProgressQueryInterface
{
    /** @return GoalProgressDTO[] */
    public function execute(string $userId): array;
}
