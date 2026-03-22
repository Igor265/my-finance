<?php

namespace App\Contexts\Finance\Domain\ValueObjects;

enum AccountType: string
{
    case Checking = 'checking';
    case Savings = 'savings';
    case Wallet = 'wallet';

    public function label(): string
    {
        return match ($this) {
            self::Checking => 'Corrente',
            self::Savings => 'Poupança',
            self::Wallet => 'Carteira'
        };
    }
}
