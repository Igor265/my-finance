<?php

use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentCategory;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('should list categories', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    EloquentCategory::factory()->create(['user_id' => $user->id]);

    $response = $this->getJson('/api/v1/categories');
    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
});

it('should not list categories unauthenticated', function () {
    $response = $this->getJson('/api/v1/categories');
    $response->assertUnauthorized();
});

it('should not list other users categories', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Sanctum::actingAs($user);

    EloquentCategory::factory()->create(['user_id' => $other->id]);

    $response = $this->getJson('/api/v1/categories');
    $response->assertOk();
    expect($response->json('data'))->toBeEmpty();
});

it('should create a category', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/categories', [
        'name' => 'Food',
        'type' => 'expense',
    ]);
    $response->assertCreated();
    expect($response->json('data.name'))->toBe('Food');
});

it('should not create a category with invalid data', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/categories', [
        'name' => 'Food',
        'type' => 'invalid',
    ]);
    $response->assertUnprocessable();
});

it('should not create a duplicate category', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    EloquentCategory::factory()->create([
        'user_id' => $user->id,
        'name' => 'Food',
        'type' => 'expense',
    ]);

    $response = $this->postJson('/api/v1/categories', [
        'name' => 'Food',
        'type' => 'expense',
    ]);
    $response->assertUnprocessable();
    expect($response->json('message'))->toBe('Category already exists');
});

it('should show a category', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $category = EloquentCategory::factory()->create(['user_id' => $user->id]);

    $response = $this->getJson("/api/v1/categories/{$category->id}");
    $response->assertOk();
    expect($response->json('data.id'))->toBe($category->id);
});

it('should return 404 for unknown category', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/categories/unknown-id');
    $response->assertNotFound();
});

it('should not show another users category', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Sanctum::actingAs($user);

    $category = EloquentCategory::factory()->create(['user_id' => $other->id]);

    $response = $this->getJson("/api/v1/categories/{$category->id}");
    $response->assertNotFound();
});
