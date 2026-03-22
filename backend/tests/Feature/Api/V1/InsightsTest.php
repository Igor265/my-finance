<?php

use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentAccount;
use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentBudget;
use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentCategory;
use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentFinancialGoal;
use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentTransaction;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('should return summary with total balance', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    EloquentAccount::factory()->create(['user_id' => $user->id, 'balance' => 100000, 'currency' => 'BRL']);
    EloquentAccount::factory()->create(['user_id' => $user->id, 'balance' => 50000, 'currency' => 'BRL']);

    $response = $this->getJson('/api/v1/insights/summary');
    $response->assertOk();
    expect($response->json('data.total_balance'))->toBe(1500)
        ->and($response->json('data.account_count'))->toBe(2)
        ->and($response->json('data.currency'))->toBe('BRL');
});

it('should not return summary unauthenticated', function () {
    $response = $this->getJson('/api/v1/insights/summary');
    $response->assertUnauthorized();
});

it('should not include other users accounts in summary', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Sanctum::actingAs($user);

    EloquentAccount::factory()->create(['user_id' => $user->id, 'balance' => 100000]);
    EloquentAccount::factory()->create(['user_id' => $other->id, 'balance' => 999999]);

    $response = $this->getJson('/api/v1/insights/summary');
    $response->assertOk();
    expect($response->json('data.total_balance'))->toBe(1000)
        ->and($response->json('data.account_count'))->toBe(1);
});

it('should return spending grouped by category for current month', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $account = EloquentAccount::factory()->create(['user_id' => $user->id]);
    $category = EloquentCategory::factory()->create(['user_id' => $user->id]);

    $today = now()->format('Y-m-d');

    EloquentTransaction::factory()->create([
        'account_id' => $account->id,
        'type' => 'expense',
        'amount' => 5000,
        'currency' => 'BRL',
        'category_id' => $category->id,
        'date' => $today,
    ]);
    EloquentTransaction::factory()->create([
        'account_id' => $account->id,
        'type' => 'expense',
        'amount' => 3000,
        'currency' => 'BRL',
        'category_id' => $category->id,
        'date' => $today,
    ]);

    $response = $this->getJson('/api/v1/insights/spending');
    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1)
        ->and($response->json('data.0.amount'))->toBe(80)
        ->and($response->json('data.0.transaction_count'))->toBe(2)
        ->and($response->json('data.0.category_id'))->toBe($category->id);
});

it('should not include income transactions in spending', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $account = EloquentAccount::factory()->create(['user_id' => $user->id]);

    EloquentTransaction::factory()->create([
        'account_id' => $account->id,
        'type' => 'income',
        'amount' => 10000,
        'date' => now()->format('Y-m-d'),
    ]);

    $response = $this->getJson('/api/v1/insights/spending');
    $response->assertOk();
    expect($response->json('data'))->toBeEmpty();
});

it('should not return spending unauthenticated', function () {
    $response = $this->getJson('/api/v1/insights/spending');
    $response->assertUnauthorized();
});

it('should return budget status with spent percentage', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $category = EloquentCategory::factory()->create(['user_id' => $user->id]);
    $account = EloquentAccount::factory()->create(['user_id' => $user->id]);

    $budget = EloquentBudget::factory()->create([
        'user_id' => $user->id,
        'category_id' => $category->id,
        'maximum_amount' => 10000,
        'currency' => 'BRL',
        'start_date' => now()->startOfMonth()->format('Y-m-d'),
        'end_date' => now()->endOfMonth()->format('Y-m-d'),
    ]);

    EloquentTransaction::factory()->create([
        'account_id' => $account->id,
        'category_id' => $category->id,
        'type' => 'expense',
        'amount' => 5000,
        'currency' => 'BRL',
        'date' => now()->format('Y-m-d'),
    ]);

    $response = $this->getJson('/api/v1/insights/budgets');
    $response->assertOk();
    expect($response->json('data.0.budget_id'))->toBe($budget->id)
        ->and($response->json('data.0.spent_amount'))->toBe(50)
        ->and($response->json('data.0.percentage'))->toBe(50);
});

it('should not return budgets unauthenticated', function () {
    $response = $this->getJson('/api/v1/insights/budgets');
    $response->assertUnauthorized();
});

it('should not include other users budgets in status', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Sanctum::actingAs($user);

    EloquentBudget::factory()->create(['user_id' => $other->id]);

    $response = $this->getJson('/api/v1/insights/budgets');
    $response->assertOk();
    expect($response->json('data'))->toBeEmpty();
});

it('should return goals progress', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $goal = EloquentFinancialGoal::factory()->create([
        'user_id' => $user->id,
        'target_amount' => 100000,
        'current_amount' => 25000,
        'currency' => 'BRL',
    ]);

    $response = $this->getJson('/api/v1/insights/goals');
    $response->assertOk();
    expect($response->json('data.0.goal_id'))->toBe($goal->id)
        ->and($response->json('data.0.percentage'))->toBe(25)
        ->and($response->json('data.0.target_amount'))->toBe(1000)
        ->and($response->json('data.0.current_amount'))->toBe(250);
});

it('should not return goals unauthenticated', function () {
    $response = $this->getJson('/api/v1/insights/goals');
    $response->assertUnauthorized();
});

it('should not include other users goals', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Sanctum::actingAs($user);

    EloquentFinancialGoal::factory()->create(['user_id' => $other->id]);

    $response = $this->getJson('/api/v1/insights/goals');
    $response->assertOk();
    expect($response->json('data'))->toBeEmpty();
});
