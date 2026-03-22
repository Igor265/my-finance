<?php

namespace Database\Factories;

use App\Contexts\Finance\Domain\ValueObjects\TransactionType;
use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<EloquentTransaction>
 */
class EloquentTransactionFactory extends Factory
{
    protected $model = EloquentTransaction::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'account_id' => (string) Str::uuid(),
            'amount' => $this->faker->numberBetween(100, 100000),
            'type' => $this->faker->randomElement(TransactionType::cases())->value,
            'description' => $this->faker->sentence(),
            'category_id' => null,
            'date' => $this->faker->date(),
        ];
    }
}
