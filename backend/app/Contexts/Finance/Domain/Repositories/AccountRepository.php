<?php

namespace App\Contexts\Finance\Domain\Repositories;

use App\Contexts\Finance\Domain\Entities\Account;
use Illuminate\Pagination\LengthAwarePaginator;

interface AccountRepository
{
    public function findById(string $id): ?Account;

    public function findByUserId(string $userId): array;
    public function paginateByUserId(string $userId, int $perPage = 15): LengthAwarePaginator;

    public function save(Account $account): void;

    public function delete(string $id): void;
}
