<?php

namespace App\Contexts\Finance\Domain\ValueObjects;

class Money
{
    readonly int $amount;
    readonly string $currency;

    public function __construct(int $amount, string $currency = 'BRL')
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public static function fromFloat(float $amount, string $currency = 'BRL'): Money
    {
        $value = (int) round($amount * 100);
        return new self($value, $currency);
    }

    public function toFloat(): float
    {
        return round($this->amount / 100, 2);
    }

    public function add(Money $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException('Currencies must be the same');
        }
        return new self($this->amount + $other->amount, $this->currency);
    }

    public function subtract(Money $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException('Currencies must be the same');
        }
        return new self($this->amount - $other->amount, $this->currency);
    }

    public function equals(Money $other): bool
    {
        if (($this->currency !== $other->currency) || ($this->amount !== $other->amount)) {
            return false;
        }
        return true;
    }
}
