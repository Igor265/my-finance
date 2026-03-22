<?php

use App\Contexts\Finance\FinanceServiceProvider;
use App\Contexts\Goals\GoalsServiceProvider;
use App\Contexts\Insights\InsightsServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    FinanceServiceProvider::class,
    GoalsServiceProvider::class,
    InsightsServiceProvider::class,
];
