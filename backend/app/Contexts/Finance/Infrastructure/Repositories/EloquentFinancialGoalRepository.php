<?php

namespace App\Contexts\Finance\Infrastructure\Repositories;

use App\Contexts\Finance\Domain\Entities\FinancialGoal;
use App\Contexts\Finance\Domain\Repositories\FinancialGoalRepository;
use App\Contexts\Finance\Domain\ValueObjects\Money;
use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentFinancialGoal;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentFinancialGoalRepository implements FinancialGoalRepository
{
    private function toDomain(EloquentFinancialGoal $model): FinancialGoal
    {
        return new FinancialGoal(
            $model->id,
            (string) $model->user_id,
            $model->name,
            new Money($model->target_amount, $model->currency),
            new Money($model->current_amount, $model->currency),
            new \DateTimeImmutable($model->deadline),
        );
    }

    public function findById(string $id): ?FinancialGoal
    {
        $model = EloquentFinancialGoal::find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function findByUserId(string $userId): array
    {
        return EloquentFinancialGoal::where('user_id', $userId)
            ->get()
            ->map(fn ($model) => $this->toDomain($model))
            ->all();
    }

    public function paginateByUserId(string $userId, int $perPage = 15): LengthAwarePaginator
    {
        return EloquentFinancialGoal::where('user_id', $userId)
            ->paginate($perPage)
            ->through(fn ($model) => $this->toDomain($model));
    }

    public function save(FinancialGoal $goal): void
    {
        EloquentFinancialGoal::updateOrCreate(
            ['id' => $goal->id],
            [
                'user_id' => $goal->userId,
                'name' => $goal->name,
                'target_amount' => $goal->targetAmount->amount,
                'current_amount' => $goal->currentAmount->amount,
                'currency' => $goal->targetAmount->currency,
                'deadline' => $goal->deadline->format('Y-m-d'),
            ]
        );
    }

    public function delete(string $id): void
    {
        EloquentFinancialGoal::destroy($id);
    }
}
