<?php

namespace App\Contexts\Insights\Application\Queries;

use App\Contexts\Finance\Domain\Repositories\AccountRepository;
use App\Contexts\Insights\Application\DTOs\SummaryDTO;
use App\Contexts\Insights\Application\Queries\Contracts\SummaryQueryInterface;

class GetSummaryQuery implements SummaryQueryInterface
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
    ) {}

    public function execute(string $userId): SummaryDTO
    {
        $accounts = $this->accountRepository->findByUserId($userId);

        $totalBalance = array_sum(
            array_map(fn($account) => $account->balance->toFloat(), $accounts)
        );

        return new SummaryDTO($totalBalance, 'BRL', count($accounts));
    }
}
