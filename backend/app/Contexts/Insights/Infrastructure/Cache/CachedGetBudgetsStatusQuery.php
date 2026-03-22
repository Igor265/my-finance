<?php

namespace App\Contexts\Insights\Infrastructure\Cache;

use App\Contexts\Insights\Application\DTOs\BudgetStatusDTO;
use App\Contexts\Insights\Application\Queries\Contracts\BudgetsStatusQueryInterface;
use Illuminate\Cache\Repository as Cache;

class CachedGetBudgetsStatusQuery implements BudgetsStatusQueryInterface
{
    private const TTL = 300;

    public function __construct(
        private readonly BudgetsStatusQueryInterface $inner,
        private readonly Cache $cache,
    ) {}

    /** @return BudgetStatusDTO[] */
    public function execute(string $userId): array
    {
        return $this->cache->remember(
            "insights:budgets:{$userId}",
            self::TTL,
            fn () => $this->inner->execute($userId),
        );
    }
}
