<?php

use App\Contexts\Finance\Domain\Entities\Transaction;
use App\Contexts\Finance\Domain\ValueObjects\Money;
use App\Contexts\Finance\Domain\ValueObjects\TransactionType;

it('should create a transaction', function () {
    $money = new Money(1000);
    $date = new DateTimeImmutable;
    $transaction = new Transaction('1', '1', $money, TransactionType::Income, 'test', '1', $date);
    expect($transaction->id)->toBe('1');
});

it('should throw an error when amount is below 1', function () {
    $money = new Money(0);
    $date = new DateTimeImmutable;
    expect(fn () => new Transaction('1', '1', $money, TransactionType::Income, 'test', '1', $date))->toThrow(InvalidArgumentException::class);
});

it('should throw an error when description is blank', function () {
    $money = new Money(1000);
    $date = new DateTimeImmutable;
    expect(fn () => new Transaction('1', '1', $money, TransactionType::Income, '', '1', $date))->toThrow(InvalidArgumentException::class);
});

it('should throw an error when description is filed with spaces', function () {
    $money = new Money(1000);
    $date = new DateTimeImmutable;
    expect(fn () => new Transaction('1', '1', $money, TransactionType::Income, '      ', '1', $date))->toThrow(InvalidArgumentException::class);
});
