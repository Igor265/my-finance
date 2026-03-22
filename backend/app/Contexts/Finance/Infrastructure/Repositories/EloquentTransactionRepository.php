<?php

namespace App\Contexts\Finance\Infrastructure\Repositories;

use App\Contexts\Finance\Domain\Entities\Transaction;
use App\Contexts\Finance\Domain\Repositories\TransactionRepository;
use App\Contexts\Finance\Domain\ValueObjects\Money;
use App\Contexts\Finance\Domain\ValueObjects\TransactionType;
use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentTransaction;

class EloquentTransactionRepository implements TransactionRepository
{
    private function toDomain(EloquentTransaction $model): Transaction
    {
        return new Transaction(
            $model->id,
            $model->account_id,
            new Money($model->amount, $model->currency),
            TransactionType::from($model->type),
            $model->description,
            $model->category_id,
            new \DateTimeImmutable($model->date),
        );
    }

    public function findById(string $id): ?Transaction
    {
        $model = EloquentTransaction::find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function save(Transaction $transaction): void
    {
        EloquentTransaction::updateOrCreate(
            ['id' => $transaction->id],
            [
                'account_id' => $transaction->accountId,
                'category_id' => $transaction->categoryId,
                'description' => $transaction->description,
                'amount' => $transaction->amount->amount,
                'currency' => $transaction->amount->currency,
                'date' => $transaction->date,
                'type' => $transaction->type->value,
            ]
        );
    }

    public function delete(string $id): void
    {
        EloquentTransaction::destroy($id);
    }

    public function findByAccountId(string $accountId): array
    {
        return EloquentTransaction::where('account_id', $accountId)
            ->get()
            ->map(fn ($model) => $this->toDomain($model))
            ->all();
    }
}
