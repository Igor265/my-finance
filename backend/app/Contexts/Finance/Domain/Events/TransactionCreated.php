<?php

namespace App\Contexts\Finance\Domain\Events;

use App\Contexts\Finance\Domain\ValueObjects\Money;
use App\Contexts\Finance\Domain\ValueObjects\TransactionType;
use App\Contexts\Shared\Domain\DomainEvent;

class TransactionCreated extends DomainEvent
{
    public readonly string $transactionId;

    public readonly string $accountId;

    public readonly Money $amount;

    public readonly TransactionType $type;

    public function __construct(string $transactionId, string $accountId, Money $amount, TransactionType $type)
    {
        parent::__construct();
        $this->transactionId = $transactionId;
        $this->accountId = $accountId;
        $this->amount = $amount;
        $this->type = $type;
    }

    public function eventName(): string
    {
        return 'finance.transaction.created';
    }
}
