<?php

namespace App\Http\Controllers\Api\V1;

use App\Contexts\Insights\Application\Queries\Contracts\BudgetsStatusQueryInterface;
use App\Contexts\Insights\Application\Queries\Contracts\GoalsProgressQueryInterface;
use App\Contexts\Insights\Application\Queries\Contracts\SpendingQueryInterface;
use App\Contexts\Insights\Application\Queries\Contracts\SummaryQueryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BudgetStatusResource;
use App\Http\Resources\Api\V1\GoalProgressResource;
use App\Http\Resources\Api\V1\SpendingItemResource;
use App\Http\Resources\Api\V1\SummaryResource;
use Illuminate\Http\Request;

class InsightsController extends Controller
{
    public function __construct(
        private readonly SummaryQueryInterface $getSummaryQuery,
        private readonly SpendingQueryInterface $getSpendingQuery,
        private readonly BudgetsStatusQueryInterface $getBudgetsStatusQuery,
        private readonly GoalsProgressQueryInterface $getGoalsProgressQuery,
    ) {}

    /**
     * Financial summary
     *
     * Returns the total balance across all accounts and account count for the authenticated user.
     */
    public function summary(Request $request): SummaryResource
    {
        $summary = $this->getSummaryQuery->execute((string) $request->user()->id);

        return new SummaryResource($summary);
    }

    /**
     * Spending by category
     *
     * Returns expenses grouped by category for the current month.
     */
    public function spending(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $items = $this->getSpendingQuery->execute((string) $request->user()->id);

        return SpendingItemResource::collection($items);
    }

    /**
     * Budget status
     *
     * Returns each budget with how much was spent vs the maximum limit and the percentage used.
     */
    public function budgets(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $statuses = $this->getBudgetsStatusQuery->execute((string) $request->user()->id);

        return BudgetStatusResource::collection($statuses);
    }

    /**
     * Goals progress
     *
     * Returns each financial goal with current progress percentage towards the target amount.
     */
    public function goals(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $goals = $this->getGoalsProgressQuery->execute((string) $request->user()->id);

        return GoalProgressResource::collection($goals);
    }
}
