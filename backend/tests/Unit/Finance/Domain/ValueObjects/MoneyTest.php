<?php

use App\Contexts\Finance\Domain\ValueObjects\Money;

it('should correct convert to cents', function () {
    $money = Money::fromFloat(100.99);
    expect($money->amount)->toBe(10099);
});

it('should correct convert to reais', function () {
    $money = new Money(10099);
    expect($money->toFloat())->toBe(100.99);
});

it('should correct add money', function () {
    $money1 = new Money(100);
    $money2 = new Money(100);
    $addMoney = $money1->add($money2);
    expect($addMoney->amount)->toBe(200);
});

it('should throw an error when add and currency is different', function () {
    $money1 = new Money(100, 'BRL');
    $money2 = new Money(100, 'USD');
    expect(fn () => $money1->add($money2))->toThrow(InvalidArgumentException::class);
});

it('should subtract money', function () {
    $money1 = new Money(100);
    $money2 = new Money(100);
    $subMoney = $money1->subtract($money2);
    expect($subMoney->amount)->toBe(0);
});

it('should throw an error when subtracting and currency is different', function () {
    $money1 = new Money(100, 'BRL');
    $money2 = new Money(100, 'USD');
    expect(fn () => $money1->subtract($money2))->toThrow(InvalidArgumentException::class);
});
