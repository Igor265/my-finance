<?php

namespace App\Contexts\Insights\Application\Queries\Contracts;

use App\Contexts\Insights\Application\DTOs\SummaryDTO;

interface SummaryQueryInterface
{
    public function execute(string $userId): SummaryDTO;
}
