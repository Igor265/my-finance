<?php

use App\Contexts\Finance\Domain\Events\BudgetExceeded;
use App\Contexts\Finance\Domain\Events\GoalAchieved;
use App\Contexts\Finance\Domain\Events\TransactionCreated;
use App\Contexts\Finance\Domain\ValueObjects\Money;
use App\Contexts\Finance\Domain\ValueObjects\TransactionType;

it('should create a TransactionCreated event with correct attributes', function () {
    $amount = new Money(50000);
    $event = new TransactionCreated('txn-1', 'acc-1', $amount, TransactionType::Income);

    expect($event->transactionId)->toBe('txn-1')
        ->and($event->accountId)->toBe('acc-1')
        ->and($event->amount)->toBe($amount)
        ->and($event->type)->toBe(TransactionType::Income)
        ->and($event->eventName())->toBe('finance.transaction.created')
        ->and($event->occurredAt())->toBeInstanceOf(DateTimeImmutable::class);
});

it('should create a BudgetExceeded event with correct attributes', function () {
    $spent = new Money(110000);
    $maximum = new Money(100000);
    $event = new BudgetExceeded('bgt-1', 'cat-1', $spent, $maximum);

    expect($event->budgetId)->toBe('bgt-1')
        ->and($event->categoryId)->toBe('cat-1')
        ->and($event->spent)->toBe($spent)
        ->and($event->maximum)->toBe($maximum)
        ->and($event->eventName())->toBe('finance.budget.exceeded')
        ->and($event->occurredAt())->toBeInstanceOf(DateTimeImmutable::class);
});

it('should create a GoalAchieved event with correct attributes', function () {
    $target = new Money(1000000);
    $event = new GoalAchieved('goal-1', 'Viagem', $target);

    expect($event->goalId)->toBe('goal-1')
        ->and($event->name)->toBe('Viagem')
        ->and($event->targetAmount)->toBe($target)
        ->and($event->eventName())->toBe('finance.goal.achieved')
        ->and($event->occurredAt())->toBeInstanceOf(DateTimeImmutable::class);
});
