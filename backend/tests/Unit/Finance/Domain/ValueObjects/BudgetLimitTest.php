<?php

use App\Contexts\Finance\Domain\ValueObjects\Money;
use App\Contexts\Finance\Domain\ValueObjects\BudgetLimit;

it('should throw an error when alert percentage is higher than 100', function () {
    expect(fn() => new BudgetLimit(new Money(10000), 101))
        ->toThrow(\InvalidArgumentException::class);
});

it('should throw an error when alert percentage is below 1', function () {
    expect(fn() => new BudgetLimit(new Money(10000), 0))
        ->toThrow(\InvalidArgumentException::class);
});

it('should throw return the correct alert threshold value', function () {
    $budgetLimit = new BudgetLimit(new Money(100000), 80);
    $alert = $budgetLimit->alertThreshold();
    expect($alert->amount)->toBe(80000);
});
