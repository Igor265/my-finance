<?php

namespace App\Contexts\Finance\Infrastructure\Repositories;

use App\Contexts\Finance\Domain\Entities\Category;
use App\Contexts\Finance\Domain\Repositories\CategoryRepository;
use App\Contexts\Finance\Domain\ValueObjects\TransactionType;
use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentCategory;

class EloquentCategoryRepository implements CategoryRepository
{
    private function toDomain(EloquentCategory $model): Category
    {
        return new Category(
            $model->id,
            $model->user_id,
            $model->name,
            TransactionType::from($model->type),
        );
    }

    public function findById(string $id): ?Category
    {
        $model = EloquentCategory::find($id);
        return $model ? $this->toDomain($model) : null;
    }

    public function findByUserId(string $userId): array
    {
        return EloquentCategory::where('user_id', $userId)
            ->get()
            ->map(fn($model) => $this->toDomain($model))
            ->all();
    }

    public function findByUserIdAndName(string $userId, string $name): ?Category
    {
        $model = EloquentCategory::where('user_id', $userId)->where('name', $name)->first();
        return $model ? $this->toDomain($model) : null;
    }

    public function save(Category $category): void
    {
        EloquentCategory::updateOrCreate(
            ['id' => $category->id],
            [
                'user_id' => $category->userId,
                'name' => $category->name,
                'type' => $category->type->value,
            ]
        );
    }

    public function delete(string $id): void
    {
        EloquentCategory::destroy($id);
    }
}
