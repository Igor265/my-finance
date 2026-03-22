<?php

namespace Database\Factories;

use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentFinancialGoal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<EloquentFinancialGoal>
 */
class EloquentFinancialGoalFactory extends Factory
{
    protected $model = EloquentFinancialGoal::class;

    public function definition(): array
    {
        return [
            'id'             => (string) Str::uuid(),
            'user_id'        => User::factory(),
            'name'           => $this->faker->words(3, true),
            'target_amount'  => $this->faker->numberBetween(10000, 1000000),
            'current_amount' => $this->faker->numberBetween(0, 10000),
            'currency'       => 'BRL',
            'deadline'       => $this->faker->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
        ];
    }
}
