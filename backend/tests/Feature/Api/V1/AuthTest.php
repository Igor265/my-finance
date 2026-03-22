<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('should login', function () {
    $user = User::factory()->create();

    $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertOk()->assertJsonStructure(['token']);
});

it('should not login when passing wrong credentials', function () {
    $user = User::factory()->create();

    $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'password1',
    ])->assertUnauthorized();
});

it('should not login when passing invalid data', function () {
    $user = User::factory()->create();

    $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'pass',
    ])->assertUnprocessable();
});

it('should logout', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/auth/logout');

    $response->assertOk();
    expect($user->tokens()->count())->toBe(0);
});

it('should not logout when token doesnt exist', function () {
    $response = $this->postJson('/api/v1/auth/logout');

    $response->assertUnauthorized();
});
