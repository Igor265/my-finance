<?php

namespace App\Contexts\Finance\Domain\Repositories;

use App\Contexts\Finance\Domain\Entities\Budget;

interface BudgetRepository
{
    public function findById(string $id): ?Budget;
    public function findByUserId(string $userId): array;
    public function save(Budget $budget): void;
    public function delete(string $id): void;
}
