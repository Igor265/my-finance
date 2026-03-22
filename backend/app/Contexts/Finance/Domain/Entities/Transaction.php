<?php

namespace App\Contexts\Finance\Domain\Entities;

use App\Contexts\Finance\Domain\ValueObjects\Money;
use App\Contexts\Finance\Domain\ValueObjects\TransactionType;

class Transaction
{
    public readonly string $id;

    public readonly string $accountId;

    public readonly Money $amount;

    public readonly TransactionType $type;

    public readonly string $description;

    public readonly ?string $categoryId;

    public readonly \DateTimeImmutable $date;

    public function __construct(string $id, string $accountId, Money $amount, TransactionType $type, string $description, ?string $categoryId, \DateTimeImmutable $date)
    {
        if ($amount->amount <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero');
        }
        if (trim($description) === '') {
            throw new \InvalidArgumentException('Description is required');
        }
        $this->id = $id;
        $this->accountId = $accountId;
        $this->amount = $amount;
        $this->type = $type;
        $this->description = $description;
        $this->categoryId = $categoryId;
        $this->date = $date;
    }
}
