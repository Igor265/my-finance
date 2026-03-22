<?php

namespace App\Contexts\Insights\Application\Queries;

use App\Contexts\Finance\Domain\Entities\Budget;
use App\Contexts\Finance\Domain\Repositories\AccountRepository;
use App\Contexts\Finance\Domain\Repositories\BudgetRepository;
use App\Contexts\Finance\Domain\Repositories\TransactionRepository;
use App\Contexts\Finance\Domain\ValueObjects\TransactionType;
use App\Contexts\Insights\Application\DTOs\BudgetStatusDTO;

class GetBudgetsStatusQuery
{
    public function __construct(
        private readonly BudgetRepository $budgetRepository,
        private readonly AccountRepository $accountRepository,
        private readonly TransactionRepository $transactionRepository,
    ) {}

    /** @return BudgetStatusDTO[] */
    public function execute(string $userId): array
    {
        $budgets = $this->budgetRepository->findByUserId($userId);
        $accounts = $this->accountRepository->findByUserId($userId);
        $accountIds = array_map(fn($a) => $a->id, $accounts);

        return array_map(function (Budget $budget) use ($accountIds) {
            $transactions = $this->transactionRepository->findByAccountIdsAndPeriod($accountIds,
                $budget->period);

            $spent = 0.0;
            foreach ($transactions as $t) {
                if ($t->type === TransactionType::Expense && $t->categoryId === $budget->categoryId) {
                    $spent += $t->amount->toFloat();
                }
            }

            $maximum = $budget->limit->maximum->toFloat();
            $percentage = $maximum > 0 ? round(($spent / $maximum) * 100, 2) : 0.0;

            return new BudgetStatusDTO(
                $budget->id,
                $budget->categoryId,
                $maximum,
                $spent,
                $percentage,
                $budget->limit->maximum->currency,
                $budget->period->startDate->format('Y-m-d'),
                $budget->period->endDate->format('Y-m-d'),
            );
        }, $budgets);
    }
}
