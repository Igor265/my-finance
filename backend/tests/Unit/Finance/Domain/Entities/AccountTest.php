<?php

use App\Contexts\Finance\Domain\Entities\Account;
use App\Contexts\Finance\Domain\ValueObjects\AccountType;
use App\Contexts\Finance\Domain\ValueObjects\Money;

it('should return a new account with the correct balance after a deposit', function () {
    $money = new Money(1000);
    $depositMoney = new Money(1000);
    $account = new Account('1', 'user-1', 'test', $money, AccountType::Checking);
    $newBalance = $account->deposit($depositMoney);
    expect($newBalance->balance->amount)->toBe(2000);
});

it('should not change the original account after a deposit', function () {
    $money = new Money(1000);
    $depositMoney = new Money(1000);
    $account = new Account('1', 'user-1', 'test', $money, AccountType::Checking);
    $newBalance = $account->deposit($depositMoney);
    expect($account->balance->amount)->toBe(1000);
});

it('should return a new account with the correct balance after a withdrawal', function () {
    $money = new Money(1000);
    $withdrawalMoney = new Money(500);
    $account = new Account('1', 'user-1', 'test', $money, AccountType::Checking);
    $newBalance = $account->withdraw($withdrawalMoney);
    expect($newBalance->balance->amount)->toBe(500);
});

it('should not change the original account after a withdrawal', function () {
    $money = new Money(1000);
    $withdrawalMoney = new Money(500);
    $account = new Account('1', 'user-1', 'test', $money, AccountType::Checking);
    $newBalance = $account->withdraw($withdrawalMoney);
    expect($account->balance->amount)->toBe(1000);
});

it('should return the correct account type', function () {
    $account = new Account('1', 'user-1', 'test', new Money(1000), AccountType::Checking);
    expect($account->type)->toBe(AccountType::Checking);
});

it('should throw an error when trying to withdraw with no funds', function () {
    $money = new Money(1000);
    $withdrawalMoney = new Money(2000);
    $account = new Account('1', 'user-1', 'test', $money, AccountType::Checking);
    expect(fn () => $account->withdraw($withdrawalMoney))->toThrow(DomainException::class);
});

it('should throw an error when trying to withdraw with different currencies', function () {
    $money = new Money(1000);
    $withdrawalMoney = new Money(500, 'USD');
    $account = new Account('1', 'user-1', 'test', $money, AccountType::Checking);
    expect(fn () => $account->withdraw($withdrawalMoney))->toThrow(DomainException::class);
});
