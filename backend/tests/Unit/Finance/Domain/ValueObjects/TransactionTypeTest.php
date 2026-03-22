<?php

use App\Contexts\Finance\Domain\ValueObjects\TransactionType;

it('should return the correct case', function () {
    $income = TransactionType::from('income');
    expect($income)->toBe(TransactionType::Income);
});

it('should return the correct value', function () {
    $income = TransactionType::from('income');
    expect($income->value)->toBe('income');
});

it('should return the correct label', function () {
    $income = TransactionType::from('income')->label();
    expect($income)->toBe('Receita');
});

it('should throw an error when passed an invalid value', function () {
    expect(fn () => TransactionType::from('error'))->toThrow(ValueError::class);
});
