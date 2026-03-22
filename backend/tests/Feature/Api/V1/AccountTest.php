<?php

use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentAccount;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('should list accounts', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/accounts');
    $response->assertOk();
});

it('should not list accounts unauthenticated', function () {
    $response = $this->getJson('/api/v1/accounts');
    $response->assertUnauthorized();
});

it('should create an account', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $account = [
        'name' => 'test',
        'type' => 'checking',
    ];

    $response = $this->postJson('/api/v1/accounts', $account);
    $response->assertCreated();
    expect($response->json('data.name'))->toBe($account['name']);
});

it('should not create an account with invalid data', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $account = [
        'name' => 'test',
        'type' => 'error',
    ];

    $response = $this->postJson('/api/v1/accounts', $account);
    $response->assertUnprocessable();
});

it('should show an accounts', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $account = EloquentAccount::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->getJson("/api/v1/accounts/{$account->id}");
    $response->assertOk();
    expect($response->json('data.name'))->toBe($account->name);
});

it('should returns 404 for unknown account', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/accounts/1');
    $response->assertNotFound();
});

it('should not list other users accounts', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Sanctum::actingAs($user);

    EloquentAccount::factory()->create(['user_id' => $other->id]);

    $response = $this->getJson('/api/v1/accounts');
    $response->assertOk();
    expect($response->json('data'))->toBeEmpty();
});

it('should show only own account', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Sanctum::actingAs($user);

    $account = EloquentAccount::factory()->create(['user_id' => $other->id]);

    $response = $this->getJson("/api/v1/accounts/{$account->id}");
    $response->assertNotFound();
});

it('should paginate accounts', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    EloquentAccount::factory()->count(5)->create(['user_id' => $user->id]);

    $response = $this->getJson('/api/v1/accounts?per_page=2');
    $response->assertOk();
    expect($response->json('data'))->toHaveCount(2);
    expect($response->json('meta.total'))->toBe(5);
    expect($response->json('meta.per_page'))->toBe(2);
    expect($response->json('meta.last_page'))->toBe(3);
    expect($response->json('links.next'))->not->toBeNull();
});

it('should navigate to second page of accounts', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    EloquentAccount::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->getJson('/api/v1/accounts?per_page=2&page=2');
    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('meta.current_page'))->toBe(2);
    expect($response->json('links.prev'))->not->toBeNull();
    expect($response->json('links.next'))->toBeNull();
});

it('should create an account with initial amount reflected in balance', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/accounts', [
        'name' => 'Savings',
        'type' => 'savings',
        'initial_amount' => 500.00,
        'currency' => 'BRL',
    ]);

    $response->assertCreated();
    expect($response->json('data.balance'))->toBe(500);
});
