<?php

namespace App\Contexts\Finance\Domain\Entities;

use App\Contexts\Finance\Domain\Exceptions\InsufficientFundsException;
use App\Contexts\Finance\Domain\ValueObjects\AccountType;
use App\Contexts\Finance\Domain\ValueObjects\Money;

class Account
{
    public readonly string $id;

    public readonly string $userId;

    public readonly string $name;

    public readonly Money $balance;

    public readonly AccountType $type;

    public function __construct(string $id, string $userId, string $name, Money $balance, AccountType $type)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->balance = $balance;
        $this->type = $type;
    }

    public function deposit(Money $amount): self
    {
        return new self($this->id, $this->userId, $this->name, $this->balance->add($amount), $this->type);
    }

    public function withdraw(Money $amount): self
    {
        if ($this->balance->currency !== $amount->currency) {
            throw new \DomainException("Cannot withdraw {$amount->currency} from a {$this->balance->currency} account");
        }
        if ($this->balance->amount < $amount->amount) {
            throw new InsufficientFundsException('Insufficient funds');
        }

        return new self($this->id, $this->userId, $this->name, $this->balance->subtract($amount), $this->type);
    }
}
