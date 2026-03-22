<?php

namespace App\Contexts\Finance\Infrastructure\Repositories;

use App\Contexts\Finance\Domain\Entities\Account;
use App\Contexts\Finance\Domain\Repositories\AccountRepository;
use App\Contexts\Finance\Domain\ValueObjects\AccountType;
use App\Contexts\Finance\Domain\ValueObjects\Money;
use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentAccount;

class EloquentAccountRepository implements AccountRepository
{
    private function toDomain(EloquentAccount $model): Account
    {
        return new Account(
            $model->id,
            $model->user_id,
            $model->name,
            new Money($model->balance, $model->currency),
            AccountType::from($model->type),
        );
    }

    public function findById(string $id): ?Account
    {
        $model = EloquentAccount::find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function findByUserId(string $userId): array
    {
        return EloquentAccount::where('user_id', $userId)
            ->get()
            ->map(fn ($model) => $this->toDomain($model))
            ->all();
    }

    public function save(Account $account): void
    {
        EloquentAccount::updateOrCreate(
            ['id' => $account->id],
            [
                'user_id' => $account->userId,
                'name' => $account->name,
                'balance' => $account->balance->amount,
                'currency' => $account->balance->currency,
                'type' => $account->type->value,
            ]
        );
    }

    public function delete(string $id): void
    {
        EloquentAccount::destroy($id);
    }
}
