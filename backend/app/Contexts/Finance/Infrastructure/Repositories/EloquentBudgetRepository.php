<?php

namespace App\Contexts\Finance\Infrastructure\Repositories;

use App\Contexts\Finance\Domain\Entities\Budget;
use App\Contexts\Finance\Domain\Repositories\BudgetRepository;
use App\Contexts\Finance\Domain\ValueObjects\BudgetLimit;
use App\Contexts\Finance\Domain\ValueObjects\Money;
use App\Contexts\Finance\Domain\ValueObjects\Period;
use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentBudget;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentBudgetRepository implements BudgetRepository
{
    private function toDomain(EloquentBudget $model): Budget
    {
        return new Budget(
            $model->id,
            (string) $model->user_id,
            $model->category_id,
            new BudgetLimit(new Money($model->maximum_amount, $model->currency), $model->alert_percentage),
            Period::fromStrings($model->start_date, $model->end_date)
        );
    }

    public function findById(string $id): ?Budget
    {
        $model = EloquentBudget::find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function findByUserId(string $userId): array
    {
        return EloquentBudget::where('user_id', $userId)
            ->get()
            ->map(fn ($model) => $this->toDomain($model))
            ->all();
    }

    public function paginateByUserId(string $userId, int $perPage = 15): LengthAwarePaginator
    {
        return EloquentBudget::where('user_id', $userId)
            ->paginate($perPage)
            ->through(fn ($model) => $this->toDomain($model));
    }

    public function save(Budget $budget): void
    {
        EloquentBudget::updateOrCreate(
            ['id' => $budget->id],
            [
                'user_id' => $budget->userId,
                'category_id' => $budget->categoryId,
                'maximum_amount' => $budget->limit->maximum->amount,
                'currency' => $budget->limit->maximum->currency,
                'alert_percentage' => $budget->limit->alertPercentage,
                'start_date' => $budget->period->startDate->format('Y-m-d'),
                'end_date' => $budget->period->endDate->format('Y-m-d'),
            ]
        );
    }

    public function delete(string $id): void
    {
        EloquentBudget::destroy($id);
    }
}
