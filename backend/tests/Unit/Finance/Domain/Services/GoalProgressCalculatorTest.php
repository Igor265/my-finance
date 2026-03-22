<?php

use App\Contexts\Finance\Domain\Entities\FinancialGoal;
use App\Contexts\Finance\Domain\Services\GoalProgressCalculator;
use App\Contexts\Finance\Domain\ValueObjects\Money;

it('should return the percentage from a goal', function () {
    $goal = new FinancialGoal('1', 'user-1', 'test', Money::fromFloat(10000), Money::fromFloat(5000), new DateTimeImmutable('2027-01-01'));
    $calculate = new GoalProgressCalculator;
    expect($calculate->percentage($goal))->toBe(50.0);
});

it('should return 0 percent when current amount is zero', function () {
    $goal = new FinancialGoal('1', 'user-1', 'test', Money::fromFloat(10000), Money::fromFloat(0), new DateTimeImmutable('2027-01-01'));
    $calculate = new GoalProgressCalculator;
    expect($calculate->percentage($goal))->toBe(0.0);
});

it('should return 100 percent when goal is achieved', function () {
    $goal = new FinancialGoal('1', 'user-1', 'test', Money::fromFloat(10000), Money::fromFloat(10000), new DateTimeImmutable('2027-01-01'));
    $calculate = new GoalProgressCalculator;
    expect($calculate->percentage($goal))->toBe(100.0);
});

it('should return more than 100 percent when goal is exceeded', function () {
    $goal = new FinancialGoal('1', 'user-1', 'test', Money::fromFloat(10000), Money::fromFloat(12000), new DateTimeImmutable('2027-01-01'));
    $calculate = new GoalProgressCalculator;
    expect($calculate->percentage($goal))->toBeGreaterThan(100.0);
});
