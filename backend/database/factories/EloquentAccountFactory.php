<?php

namespace Database\Factories;

use App\Contexts\Finance\Domain\ValueObjects\AccountType;
use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentAccount;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<EloquentAccount>
 */
class EloquentAccountFactory extends Factory
{
    protected $model = EloquentAccount::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'user_id' => (string) Str::uuid(),
            'name' => $this->faker->words(2, true),
            'balance' => $this->faker->numberBetween(0, 100000),
            'currency' => 'BRL',
            'type' => $this->faker->randomElement(AccountType::cases())->value,
        ];
    }
}
