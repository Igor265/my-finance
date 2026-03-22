<?php

use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentAccount;
use App\Contexts\Finance\Infrastructure\Persistence\Models\EloquentTransaction;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('should list transactions by account', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $account = EloquentAccount::factory()->create([
        'user_id' => $user->id,
    ]);
    $transaction = EloquentTransaction::factory()->create([
        'account_id' => $account->id,
    ]);

    $response = $this->getJson("/api/v1/accounts/{$account->id}/transactions");
    $response->assertOk();
    expect($response->json('data.0.id'))->toBe((string) $transaction->id);
});

it('should paginate transactions by account', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $account = EloquentAccount::factory()->create(['user_id' => $user->id]);
    EloquentTransaction::factory()->count(5)->create(['account_id' => $account->id]);

    $response = $this->getJson("/api/v1/accounts/{$account->id}/transactions?per_page=2");
    $response->assertOk();
    expect($response->json('data'))->toHaveCount(2);
    expect($response->json('meta.total'))->toBe(5);
    expect($response->json('meta.per_page'))->toBe(2);
    expect($response->json('meta.last_page'))->toBe(3);
});

it('should navigate to second page of transactions', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $account = EloquentAccount::factory()->create(['user_id' => $user->id]);
    EloquentTransaction::factory()->count(3)->create(['account_id' => $account->id]);

    $response = $this->getJson("/api/v1/accounts/{$account->id}/transactions?per_page=2&page=2");
    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('meta.current_page'))->toBe(2);
    expect($response->json('links.next'))->toBeNull();
});

it('should not list transactions for unauthenticated', function () {
    $user = User::factory()->create();
    $account = EloquentAccount::factory()->create([
        'user_id' => $user->id,
    ]);
    $response = $this->getJson("/api/v1/accounts/{$account->id}/transactions");
    $response->assertUnauthorized();
});

it('should create a transaction', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $account = EloquentAccount::factory()->create([
        'user_id' => $user->id,
    ]);

    $transaction = [
        'account_id' => $account->id,
        'amount' => 10000,
        'type' => 'income',
        'description' => 'test',
        'date' => '2026-03-22',
    ];

    $response = $this->postJson('/api/v1/transactions', $transaction);
    $response->assertCreated();
    expect($response->json('data.account_id'))->toBe($transaction['account_id']);
});

it('should not create a transaction with invalid data', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $account = EloquentAccount::factory()->create([
        'user_id' => $user->id,
    ]);

    $transaction = [
        'account_id' => $account->id,
        'amount' => 10000,
        'type' => 'error',
        'description' => 'test',
        'date' => '2026-03-22',
    ];

    $response = $this->postJson('/api/v1/transactions', $transaction);
    $response->assertUnprocessable();
});

it('should show a transaction', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $account = EloquentAccount::factory()->create([
        'user_id' => $user->id,
    ]);
    $transaction = EloquentTransaction::factory()->create([
        'account_id' => $account->id,
    ]);

    $response = $this->getJson("/api/v1/transactions/$transaction->id");
    $response->assertOk();
    expect($response->json('data.id'))->toBe((string) $transaction->id);
});

it('should returns 404 for a unknown transaction', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/transactions/1');
    $response->assertNotFound();
});

it('should not list transactions from another users account', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Sanctum::actingAs($user);

    $account = EloquentAccount::factory()->create(['user_id' => $other->id]);

    $response = $this->getJson("/api/v1/accounts/{$account->id}/transactions");
    $response->assertNotFound();
});

it('should not show a transaction from another user', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Sanctum::actingAs($user);

    $account = EloquentAccount::factory()->create(['user_id' => $other->id]);
    $transaction = EloquentTransaction::factory()->create(['account_id' => $account->id]);

    $response = $this->getJson("/api/v1/transactions/{$transaction->id}");
    $response->assertNotFound();
});

it('should not create a transaction for another users account', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Sanctum::actingAs($user);

    $account = EloquentAccount::factory()->create(['user_id' => $other->id]);

    $transaction = [
        'account_id' => $account->id,
        'amount' => 10000,
        'type' => 'income',
        'description' => 'test',
        'date' => '2026-03-22',
    ];

    $response = $this->postJson('/api/v1/transactions', $transaction);
    $response->assertForbidden();
});
