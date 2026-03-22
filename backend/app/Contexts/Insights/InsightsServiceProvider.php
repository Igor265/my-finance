<?php

namespace App\Contexts\Insights;

use App\Contexts\Insights\Application\Queries\Contracts\BudgetsStatusQueryInterface;
use App\Contexts\Insights\Application\Queries\Contracts\GoalsProgressQueryInterface;
use App\Contexts\Insights\Application\Queries\Contracts\SpendingQueryInterface;
use App\Contexts\Insights\Application\Queries\Contracts\SummaryQueryInterface;
use App\Contexts\Insights\Application\Queries\GetBudgetsStatusQuery;
use App\Contexts\Insights\Application\Queries\GetGoalsProgressQuery;
use App\Contexts\Insights\Application\Queries\GetSpendingQuery;
use App\Contexts\Insights\Application\Queries\GetSummaryQuery;
use App\Contexts\Insights\Infrastructure\Cache\CachedGetBudgetsStatusQuery;
use App\Contexts\Insights\Infrastructure\Cache\CachedGetGoalsProgressQuery;
use App\Contexts\Insights\Infrastructure\Cache\CachedGetSpendingQuery;
use App\Contexts\Insights\Infrastructure\Cache\CachedGetSummaryQuery;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Support\ServiceProvider;

class InsightsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SummaryQueryInterface::class, function ($app) {
            return new CachedGetSummaryQuery(
                $app->make(GetSummaryQuery::class),
                $app->make(Cache::class),
            );
        });

        $this->app->bind(SpendingQueryInterface::class, function ($app) {
            return new CachedGetSpendingQuery(
                $app->make(GetSpendingQuery::class),
                $app->make(Cache::class),
            );
        });

        $this->app->bind(BudgetsStatusQueryInterface::class, function ($app) {
            return new CachedGetBudgetsStatusQuery(
                $app->make(GetBudgetsStatusQuery::class),
                $app->make(Cache::class),
            );
        });

        $this->app->bind(GoalsProgressQueryInterface::class, function ($app) {
            return new CachedGetGoalsProgressQuery(
                $app->make(GetGoalsProgressQuery::class),
                $app->make(Cache::class),
            );
        });
    }

    public function boot(): void
    {
        //
    }
}
