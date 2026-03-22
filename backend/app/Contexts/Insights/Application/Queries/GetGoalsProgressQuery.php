<?php

namespace App\Contexts\Insights\Application\Queries;

use App\Contexts\Finance\Domain\Entities\FinancialGoal;
use App\Contexts\Finance\Domain\Repositories\FinancialGoalRepository;
use App\Contexts\Insights\Application\DTOs\GoalProgressDTO;

class GetGoalsProgressQuery
{
    public function __construct(
        private readonly FinancialGoalRepository $financialGoalRepository,
    ) {}

    /** @return GoalProgressDTO[] */
    public function execute(string $userId): array
    {
        $goals = $this->financialGoalRepository->findByUserId($userId);

        return array_map(function (FinancialGoal $goal) {
            $target = $goal->targetAmount->toFloat();
            $current = $goal->currentAmount->toFloat();
            $percentage = $target > 0 ? round(($current / $target) * 100, 2) : 0.0;

            return new GoalProgressDTO(
                $goal->id,
                $goal->name,
                $target,
                $current,
                $percentage,
                $goal->targetAmount->currency,
                $goal->deadline->format('Y-m-d'),
            );
        }, $goals);
    }
}
