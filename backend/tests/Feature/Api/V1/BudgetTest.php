<?php

use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentBudget;
use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentCategory;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;

it('should list budgets', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    EloquentBudget::factory()->create(['user_id' => $user->id]);

    $response = $this->getJson('/api/v1/budgets');
    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
});

it('should not list budgets unauthenticated', function () {
    $response = $this->getJson('/api/v1/budgets');
    $response->assertUnauthorized();
});

it('should not list other users budgets', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Sanctum::actingAs($user);

    EloquentBudget::factory()->create(['user_id' => $other->id]);

    $response = $this->getJson('/api/v1/budgets');
    $response->assertOk();
    expect($response->json('data'))->toBeEmpty();
});

it('should create a budget', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $category = EloquentCategory::factory()->create(['user_id' => $user->id]);

    $response = $this->postJson('/api/v1/budgets', [
        'category_id' => $category->id,
        'maximum_amount' => 1000.00,
        'alert_percentage' => 80,
        'start_date' => '2026-01-01',
        'end_date' => '2026-01-31',
    ]);
    $response->assertCreated();
    expect($response->json('data.maximum_amount'))->toBe(1000);
});

it('should not create a budget with invalid data', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/budgets', [
        'category_id' => (string) Str::uuid(),
        'maximum_amount' => 1000.00,
        'alert_percentage' => 150,
        'start_date' => '2026-01-01',
        'end_date' => '2026-01-31',
    ]);
    $response->assertUnprocessable();
});

it('should paginate budgets', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    EloquentBudget::factory()->count(5)->create(['user_id' => $user->id]);

    $response = $this->getJson('/api/v1/budgets?per_page=2');
    $response->assertOk();
    expect($response->json('data'))->toHaveCount(2);
    expect($response->json('meta.total'))->toBe(5);
    expect($response->json('meta.per_page'))->toBe(2);
    expect($response->json('meta.last_page'))->toBe(3);
});

it('should navigate to second page of budgets', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    EloquentBudget::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->getJson('/api/v1/budgets?per_page=2&page=2');
    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('meta.current_page'))->toBe(2);
    expect($response->json('links.next'))->toBeNull();
});

it('should show a budget', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $budget = EloquentBudget::factory()->create(['user_id' => $user->id]);

    $response = $this->getJson("/api/v1/budgets/{$budget->id}");
    $response->assertOk();
    expect($response->json('data.id'))->toBe($budget->id);
});

it('should return 404 for unknown budget', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/budgets/unknown-id');
    $response->assertNotFound();
});

it('should not show another users budget', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Sanctum::actingAs($user);

    $budget = EloquentBudget::factory()->create(['user_id' => $other->id]);

    $response = $this->getJson("/api/v1/budgets/{$budget->id}");
    $response->assertNotFound();
});
