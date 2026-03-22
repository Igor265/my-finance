<?php

namespace App\Contexts\Finance\Domain\Services;

use App\Contexts\Finance\Domain\Entities\Transaction;
use App\Contexts\Finance\Domain\ValueObjects\Money;
use App\Contexts\Finance\Domain\ValueObjects\Period;
use App\Contexts\Finance\Domain\ValueObjects\TransactionType;

class BalanceCalculator
{
    /** @param Transaction[] $transactions */
    public function calculate(string $accountId, array $transactions, Period $period): Money
    {
        $balance = new Money(0);
        foreach ($transactions as $transaction) {
            if ($transaction->accountId !== $accountId) {
                continue;
            }
            if (! $period->contains($transaction->date)) {
                continue;
            }
            $balance = match ($transaction->type) {
                TransactionType::Income => $balance->add($transaction->amount),
                TransactionType::Expense, TransactionType::Transfer => $balance->subtract($transaction->amount),
            };
        }

        return $balance;
    }
}
