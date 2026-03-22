<?php

namespace App\Contexts\Finance\Domain\Repositories;

use App\Contexts\Finance\Domain\Entities\Transaction;
use App\Contexts\Finance\Domain\ValueObjects\Period;

interface TransactionRepository
{
    public function findById(string $id): ?Transaction;
    public function findByAccountId(string $accountId): array;

    /** @param string[] $accountIds */
    public function findByAccountIdsAndPeriod(array $accountIds, Period $period): array;

    public function save(Transaction $transaction): void;

    public function delete(string $id): void;

}
