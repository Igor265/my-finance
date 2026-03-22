<?php

namespace App\Contexts\Finance;

use App\Contexts\Finance\Domain\Repositories\AccountRepository;
use App\Contexts\Finance\Domain\Repositories\BudgetRepository;
use App\Contexts\Finance\Domain\Repositories\CategoryRepository;
use App\Contexts\Finance\Domain\Repositories\FinancialGoalRepository;
use App\Contexts\Finance\Domain\Repositories\TransactionRepository;
use App\Contexts\Finance\Infrastructure\Repositories\EloquentAccountRepository;
use App\Contexts\Finance\Infrastructure\Repositories\EloquentBudgetRepository;
use App\Contexts\Finance\Infrastructure\Repositories\EloquentCategoryRepository;
use App\Contexts\Finance\Infrastructure\Repositories\EloquentFinancialGoalRepository;
use App\Contexts\Finance\Infrastructure\Repositories\EloquentTransactionRepository;
use Illuminate\Support\ServiceProvider;

class FinanceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
         $this->app->bind(AccountRepository::class, EloquentAccountRepository::class);
         $this->app->bind(TransactionRepository::class, EloquentTransactionRepository::class);
         $this->app->bind(CategoryRepository::class, EloquentCategoryRepository::class);
         $this->app->bind(BudgetRepository::class, EloquentBudgetRepository::class);
         $this->app->bind(FinancialGoalRepository::class, EloquentFinancialGoalRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
