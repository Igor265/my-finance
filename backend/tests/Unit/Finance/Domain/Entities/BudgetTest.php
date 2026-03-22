<?php

use App\Contexts\Finance\Domain\Entities\Budget;
use App\Contexts\Finance\Domain\ValueObjects\BudgetLimit;
use App\Contexts\Finance\Domain\ValueObjects\Money;
use App\Contexts\Finance\Domain\ValueObjects\Period;

it('should create a budget', function () {
    $budgetLimit = new BudgetLimit(new Money(1000), 10);
    $period = new Period(new DateTimeImmutable('2026-01-01'), new DateTimeImmutable('2026-01-31'));
    $budget = new Budget('1', 'user-1', '1', $budgetLimit, $period);
    expect($budget->id)->toBe('1');
});
