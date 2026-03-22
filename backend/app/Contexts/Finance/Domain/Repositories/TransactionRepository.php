<?php

namespace App\Contexts\Finance\Domain\Repositories;

use App\Contexts\Finance\Domain\Entities\Transaction;
use App\Contexts\Finance\Domain\ValueObjects\Period;
use Illuminate\Pagination\LengthAwarePaginator;

interface TransactionRepository
{
    public function findById(string $id): ?Transaction;
    public function findByAccountId(string $accountId): array;

    public function paginateByAccountId(string $accountId, int $perPage = 15): LengthAwarePaginator;

    /** @param string[] $accountIds */
    public function findByAccountIdsAndPeriod(array $accountIds, Period $period): array;

    public function save(Transaction $transaction): void;

    public function delete(string $id): void;

}
