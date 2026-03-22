<?php

namespace App\Contexts\Insights\Infrastructure\Cache;

use App\Contexts\Insights\Application\DTOs\GoalProgressDTO;
use App\Contexts\Insights\Application\Queries\Contracts\GoalsProgressQueryInterface;
use Illuminate\Cache\Repository as Cache;

class CachedGetGoalsProgressQuery implements GoalsProgressQueryInterface
{
    private const TTL = 600;

    public function __construct(
        private readonly GoalsProgressQueryInterface $inner,
        private readonly Cache $cache,
    ) {}

    /** @return GoalProgressDTO[] */
    public function execute(string $userId): array
    {
        return $this->cache->remember(
            "insights:goals:{$userId}",
            self::TTL,
            fn () => $this->inner->execute($userId),
        );
    }
}
