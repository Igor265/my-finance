<?php

use App\Contexts\Finance\Domain\ValueObjects\AccountType;

it('should return the correct label for checking', function () {
    expect(AccountType::Checking->label())->toBe('Corrente');
});

it('should return the correct label for savings', function () {
    expect(AccountType::Savings->label())->toBe('Poupança');
});

it('should return the correct label for wallet', function () {
    expect(AccountType::Wallet->label())->toBe('Carteira');
});

it('should return the correct case from value', function () {
    expect(AccountType::from('checking'))->toBe(AccountType::Checking)
        ->and(AccountType::from('savings'))->toBe(AccountType::Savings)
        ->and(AccountType::from('wallet'))->toBe(AccountType::Wallet);
});

it('should throw an error when passed an invalid value', function () {
    expect(fn () => AccountType::from('invalid'))->toThrow(ValueError::class);
});
