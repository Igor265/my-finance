<?php

namespace App\Contexts\Insights\Application\Queries;

use App\Contexts\Finance\Domain\Repositories\AccountRepository;
use App\Contexts\Finance\Domain\Repositories\TransactionRepository;
use App\Contexts\Finance\Domain\ValueObjects\Period;
use App\Contexts\Finance\Domain\ValueObjects\TransactionType;
use App\Contexts\Insights\Application\DTOs\SpendingItemDTO;

class GetSpendingQuery
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly TransactionRepository $transactionRepository,
    ) {}

    /** @return SpendingItemDTO[] */
    public function execute(string $userId): array
    {
        $accounts = $this->accountRepository->findByUserId($userId);
        $accountIds = array_map(fn($a) => $a->id, $accounts);

        $now = new \DateTimeImmutable();
        $period = Period::fromStrings(
            $now->modify('first day of this month')->format('Y-m-d'),
            $now->modify('last day of this month')->format('Y-m-d'),
        );

        $transactions = $this->transactionRepository->findByAccountIdsAndPeriod($accountIds, $period);

        $groups = [];
        foreach ($transactions as $t) {
            if ($t->type !== TransactionType::Expense) {
                continue;
            }
            $key = $t->categoryId ?? 'uncategorized';
            if (!isset($groups[$key])) {
                $groups[$key] = ['categoryId' => $t->categoryId, 'amount' => 0.0, 'currency' =>
                    $t->amount->currency, 'count' => 0];
            }
            $groups[$key]['amount'] += $t->amount->toFloat();
            $groups[$key]['count']++;
        }

        return array_values(array_map(
            fn($g) => new SpendingItemDTO($g['categoryId'], $g['amount'], $g['currency'], $g['count']),
            $groups
        ));
    }
}
