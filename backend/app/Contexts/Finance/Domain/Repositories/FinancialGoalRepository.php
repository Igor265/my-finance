<?php

namespace App\Contexts\Finance\Domain\Repositories;

use App\Contexts\Finance\Domain\Entities\FinancialGoal;
use Illuminate\Pagination\LengthAwarePaginator;

interface FinancialGoalRepository
{
    public function findById(string $id): ?FinancialGoal;

    public function findByUserId(string $userId): array;

    public function paginateByUserId(string $userId, int $perPage = 15): LengthAwarePaginator;

    public function save(FinancialGoal $goal): void;

    public function delete(string $id): void;
}
