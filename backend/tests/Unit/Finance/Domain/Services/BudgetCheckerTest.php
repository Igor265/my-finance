<?php

use App\Contexts\Finance\Domain\Entities\Budget;
use App\Contexts\Finance\Domain\Services\BudgetChecker;
use App\Contexts\Finance\Domain\ValueObjects\BudgetLimit;
use App\Contexts\Finance\Domain\ValueObjects\Money;
use App\Contexts\Finance\Domain\ValueObjects\Period;

beforeEach(function () {
    $this->budgetLimit = new BudgetLimit(Money::fromFloat(1000), 80);
    $this->budgedVerification = new BudgetChecker;
    $this->period = Period::fromStrings('2026-03-01', '2026-03-31');
});

it('should be true when exceed the budged', function () {
    $budget = new Budget('1', 'user-1', '1', $this->budgetLimit, $this->period);
    $isExceeded = $this->budgedVerification->isExceeded($budget, Money::fromFloat(1100));
    expect($isExceeded)->toBeTrue();
});

it('should be false when not exceed the budged', function () {
    $budget = new Budget('1', 'user-1', '1', $this->budgetLimit, $this->period);
    $isExceeded = $this->budgedVerification->isExceeded($budget, Money::fromFloat(500));
    expect($isExceeded)->toBeFalse();
});
