<?php

use App\Contexts\Finance\Domain\Entities\Transaction;
use App\Contexts\Finance\Domain\Services\BalanceCalculator;
use App\Contexts\Finance\Domain\ValueObjects\Money;
use App\Contexts\Finance\Domain\ValueObjects\Period;
use App\Contexts\Finance\Domain\ValueObjects\TransactionType;

beforeEach(function () {
    $this->calculator = new BalanceCalculator;
    $this->period = Period::fromStrings('2026-03-01', '2026-03-31');
});

it('should sum the transactions of an account', function () {
    $transactions = [
        new Transaction('1', 'acc-1', Money::fromFloat(3000), TransactionType::Income, 'test', 'cat-1',
            new DateTimeImmutable('2026-03-05')),
        new Transaction('2', 'acc-1', Money::fromFloat(500), TransactionType::Income, 'test', 'cat-2',
            new DateTimeImmutable('2026-03-10')),
        new Transaction('3', 'acc-2', Money::fromFloat(200), TransactionType::Income, 'test', 'cat-2',
            new DateTimeImmutable('2026-03-15')),
    ];
    $money = $this->calculator->calculate('acc-1', $transactions, $this->period);
    expect($money->amount)->toBe(350000);
});

it('should sum the transactions of an account with the transactions within the period', function () {
    $transactions = [
        new Transaction('1', 'acc-1', Money::fromFloat(3000), TransactionType::Income, 'test', 'cat-1',
            new DateTimeImmutable('2026-03-05')),
        new Transaction('2', 'acc-1', Money::fromFloat(500), TransactionType::Income, 'test', 'cat-2',
            new DateTimeImmutable('2026-03-10')),
        new Transaction('3', 'acc-1', Money::fromFloat(200), TransactionType::Income, 'test', 'cat-2',
            new DateTimeImmutable('2026-02-15')),
    ];
    $money = $this->calculator->calculate('acc-1', $transactions, $this->period);
    expect($money->amount)->toBe(350000);
});

it('should subtract the transactions of an account', function () {
    $transactions = [
        new Transaction('1', 'acc-1', Money::fromFloat(3000), TransactionType::Income, 'test', 'cat-1',
            new DateTimeImmutable('2026-03-05')),
        new Transaction('2', 'acc-1', Money::fromFloat(500), TransactionType::Expense, 'test', 'cat-2',
            new DateTimeImmutable('2026-03-10')),
        new Transaction('3', 'acc-1', Money::fromFloat(500), TransactionType::Expense, 'test', 'cat-2',
            new DateTimeImmutable('2026-03-15')),
    ];
    $money = $this->calculator->calculate('acc-1', $transactions, $this->period);
    expect($money->amount)->toBe(200000);
});

it('should subtract the transfer of an account', function () {
    $transactions = [
        new Transaction('1', 'acc-1', Money::fromFloat(3000), TransactionType::Income, 'test', 'cat-1',
            new DateTimeImmutable('2026-03-05')),
        new Transaction('2', 'acc-1', Money::fromFloat(500), TransactionType::Transfer, 'test', 'cat-2',
            new DateTimeImmutable('2026-03-10')),
        new Transaction('3', 'acc-1', Money::fromFloat(500), TransactionType::Transfer, 'test', 'cat-2',
            new DateTimeImmutable('2026-03-15')),
    ];
    $money = $this->calculator->calculate('acc-1', $transactions, $this->period);
    expect($money->amount)->toBe(200000);
});
