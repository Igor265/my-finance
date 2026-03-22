<?php

namespace App\Contexts\Insights\Infrastructure\Cache;

use App\Contexts\Insights\Application\DTOs\SpendingItemDTO;
use App\Contexts\Insights\Application\Queries\Contracts\SpendingQueryInterface;
use Illuminate\Cache\Repository as Cache;

class CachedGetSpendingQuery implements SpendingQueryInterface
{
    private const TTL = 300;

    public function __construct(
        private readonly SpendingQueryInterface $inner,
        private readonly Cache $cache,
    ) {}

    /** @return SpendingItemDTO[] */
    public function execute(string $userId): array
    {
        return $this->cache->remember(
            "insights:spending:{$userId}",
            self::TTL,
            fn () => $this->inner->execute($userId),
        );
    }
}
