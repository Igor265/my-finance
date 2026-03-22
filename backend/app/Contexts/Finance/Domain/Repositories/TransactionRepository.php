<?php

namespace App\Contexts\Finance\Domain\Repositories;

use App\Contexts\Finance\Domain\Entities\Transaction;

interface TransactionRepository
{
    public function findById(string $id): ?Transaction;
    public function save(Transaction $transaction): void;
    public function delete(string $id): void;
    public function findByAccountId(string $accountId): array;
}
