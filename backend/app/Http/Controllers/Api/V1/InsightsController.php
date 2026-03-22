<?php

namespace App\Http\Controllers\Api\V1;

use App\Contexts\Insights\Application\Queries\GetBudgetsStatusQuery;
use App\Contexts\Insights\Application\Queries\GetGoalsProgressQuery;
use App\Contexts\Insights\Application\Queries\GetSpendingQuery;
use App\Contexts\Insights\Application\Queries\GetSummaryQuery;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BudgetStatusResource;
use App\Http\Resources\Api\V1\GoalProgressResource;
use App\Http\Resources\Api\V1\SpendingItemResource;
use App\Http\Resources\Api\V1\SummaryResource;
use Illuminate\Http\Request;

class InsightsController extends Controller
{
    public function __construct(
        private readonly GetSummaryQuery $getSummaryQuery,
        private readonly GetSpendingQuery $getSpendingQuery,
        private readonly GetBudgetsStatusQuery $getBudgetsStatusQuery,
        private readonly GetGoalsProgressQuery $getGoalsProgressQuery,
    ) {}

    public function summary(Request $request): SummaryResource
    {
        $summary = $this->getSummaryQuery->execute((string) $request->user()->id);

        return new SummaryResource($summary);
    }

    public function spending(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $items = $this->getSpendingQuery->execute((string) $request->user()->id);

        return SpendingItemResource::collection($items);
    }

    public function budgets(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $statuses = $this->getBudgetsStatusQuery->execute((string) $request->user()->id);

        return BudgetStatusResource::collection($statuses);
    }

    public function goals(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $goals = $this->getGoalsProgressQuery->execute((string) $request->user()->id);

        return GoalProgressResource::collection($goals);
    }
}
