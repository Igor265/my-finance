<?php

namespace Database\Factories;

use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentBudget;
use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<EloquentBudget>
 */
class EloquentBudgetFactory extends Factory
{
    protected $model = EloquentBudget::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 year', 'now');
        $end   = $this->faker->dateTimeBetween($start, '+1 year');

        return [
            'id'               => (string) Str::uuid(),
            'user_id'          => (string) Str::uuid(),
            'category_id'      => EloquentCategory::factory(),
            'maximum_amount'   => $this->faker->numberBetween(10000, 500000),
            'currency'         => 'BRL',
            'alert_percentage' => $this->faker->numberBetween(1, 100),
            'start_date'       => $start->format('Y-m-d'),
            'end_date'         => $end->format('Y-m-d'),
        ];
    }
}
