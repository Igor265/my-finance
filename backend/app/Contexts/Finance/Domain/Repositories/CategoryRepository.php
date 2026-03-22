<?php

namespace App\Contexts\Finance\Domain\Repositories;

use App\Contexts\Finance\Domain\Entities\Category;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepository
{
    public function findById(string $id): ?Category;

    public function findByUserId(string $userId): array;

    public function paginateByUserId(string $userId, int $perPage = 15): LengthAwarePaginator;

    public function findByUserIdAndName(string $userId, string $name): ?Category;

    public function save(Category $category): void;

    public function delete(string $id): void;
}
