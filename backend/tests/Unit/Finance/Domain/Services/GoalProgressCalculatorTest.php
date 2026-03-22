<?php

use App\Contexts\Finance\Domain\Entities\FinancialGoal;
use App\Contexts\Finance\Domain\Services\GoalProgressCalculator;
use App\Contexts\Finance\Domain\ValueObjects\Money;

it('should return the percentage from a goal', function () {
    $goal = new FinancialGoal('1', 'user-1', 'test', Money::fromFloat(10000), Money::fromFloat(5000), new DateTimeImmutable('2027-01-01'));
    $calculate = new GoalProgressCalculator();
    expect($calculate->percentage($goal))->toBe(50.0);
});
