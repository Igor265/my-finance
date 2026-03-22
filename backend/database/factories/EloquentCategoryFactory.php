<?php

namespace Database\Factories;

use App\Contexts\Finance\Domain\ValueObjects\TransactionType;
use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<EloquentCategory>
 */
class EloquentCategoryFactory extends Factory
{
    protected $model = EloquentCategory::class;

    public function definition(): array
    {
        return [
            'id'      => (string) Str::uuid(),
            'user_id' => User::factory(),
            'name'    => $this->faker->word(),
            'type'    => $this->faker->randomElement(TransactionType::cases())->value,
        ];
    }
}
