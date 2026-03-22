<?php

use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentFinancialGoal;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('should list financial goals', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    EloquentFinancialGoal::factory()->create(['user_id' => $user->id]);

    $response = $this->getJson('/api/v1/financial-goals');
    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
});

it('should not list financial goals unauthenticated', function () {
    $response = $this->getJson('/api/v1/financial-goals');
    $response->assertUnauthorized();
});

it('should not list other users financial goals', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Sanctum::actingAs($user);

    EloquentFinancialGoal::factory()->create(['user_id' => $other->id]);

    $response = $this->getJson('/api/v1/financial-goals');
    $response->assertOk();
    expect($response->json('data'))->toBeEmpty();
});

it('should create a financial goal', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/financial-goals', [
        'name'          => 'New car',
        'target_amount' => 50000.00,
        'deadline'      => '2027-01-01',
    ]);
    $response->assertCreated();
    expect($response->json('data.name'))->toBe('New car');
});

it('should not create a financial goal with invalid data', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/financial-goals', [
        'name'          => 'New car',
        'target_amount' => -100,
        'deadline'      => '2027-01-01',
    ]);
    $response->assertUnprocessable();
});

it('should show a financial goal', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $goal = EloquentFinancialGoal::factory()->create(['user_id' => $user->id]);

    $response = $this->getJson("/api/v1/financial-goals/{$goal->id}");
    $response->assertOk();
    expect($response->json('data.id'))->toBe($goal->id);
});

it('should return 404 for unknown financial goal', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/financial-goals/unknown-id');
    $response->assertNotFound();
});

it('should not show another users financial goal', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Sanctum::actingAs($user);

    $goal = EloquentFinancialGoal::factory()->create(['user_id' => $other->id]);

    $response = $this->getJson("/api/v1/financial-goals/{$goal->id}");
    $response->assertNotFound();
});
