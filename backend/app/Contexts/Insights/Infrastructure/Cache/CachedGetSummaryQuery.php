<?php

namespace App\Contexts\Insights\Infrastructure\Cache;

use App\Contexts\Insights\Application\DTOs\SummaryDTO;
use App\Contexts\Insights\Application\Queries\Contracts\SummaryQueryInterface;
use Illuminate\Cache\Repository as Cache;

class CachedGetSummaryQuery implements SummaryQueryInterface
{
    private const TTL = 300;

    public function __construct(
        private readonly SummaryQueryInterface $inner,
        private readonly Cache $cache,
    ) {}
    public function execute(string $userId): SummaryDTO
    {
        return $this->cache->remember(
            "insights:summary:{$userId}",
            self::TTL,
            fn () => $this->inner->execute($userId),
        );
    }
}
