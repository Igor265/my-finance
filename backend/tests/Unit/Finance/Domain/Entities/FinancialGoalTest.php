<?php

use App\Contexts\Finance\Domain\Entities\FinancialGoal;
use App\Contexts\Finance\Domain\ValueObjects\Money;

it('should create a financial goal', function () {
    $goal = new FinancialGoal('1', 'user-1','test', Money::fromFloat(10000), Money::fromFloat(0), new DateTimeImmutable('2027-01-01'));
    expect($goal->id)->toBe('1');
});

it('should throw an error when current amount is below zero', function () {
    expect(fn() => new FinancialGoal('1', 'user-1','test', Money::fromFloat(1000), Money::fromFloat(-1), new DateTimeImmutable('2027-01-01')))
        ->toThrow(\InvalidArgumentException::class);
});

it('should throw an error when target amount is zero or below', function () {
    expect(fn() => new FinancialGoal('1', 'user-1','test', Money::fromFloat(0), Money::fromFloat(0), new DateTimeImmutable('2027-01-01')))
        ->toThrow(\InvalidArgumentException::class);
});
