<?php

use App\Http\Controllers\Api\V1\AccountController;
use App\Http\Controllers\Api\V1\BudgetController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\FinancialGoalController;
use App\Http\Controllers\Api\V1\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum', 'throttle:api']);

Route::prefix('v1/auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::post('logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum', 'throttle:api']);
});

Route::prefix('v1')->middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::resource('accounts', AccountController::class)->only(['index', 'store', 'show']);
    Route::get('accounts/{accountId}/transactions', [TransactionController::class, 'index']);
    Route::post('transactions', [TransactionController::class, 'store']);
    Route::get('transactions/{id}', [TransactionController::class, 'show']);
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'show']);
    Route::resource('budgets', BudgetController::class)->only(['index', 'store', 'show']);
    Route::resource('financial-goals', FinancialGoalController::class)->only(['index', 'store', 'show']);
});
