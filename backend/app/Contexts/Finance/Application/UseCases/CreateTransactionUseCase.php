<?php

namespace App\Contexts\Finance\Application\UseCases;

use App\Contexts\Finance\Application\DTOs\CreateTransactionDTO;
use App\Contexts\Finance\Domain\Entities\Transaction;
use App\Contexts\Finance\Domain\Repositories\TransactionRepository;
use App\Contexts\Finance\Domain\ValueObjects\Money;
use App\Contexts\Finance\Domain\ValueObjects\TransactionType;
use Illuminate\Support\Str;

class CreateTransactionUseCase
{
    public readonly TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function execute(CreateTransactionDTO $dto): Transaction
    {
        $transaction = new Transaction((string) Str::uuid(), $dto->accountId, Money::fromFloat($dto->amount, $dto->currency), TransactionType::from($dto->type), $dto->description, $dto->categoryId, new \DateTimeImmutable($dto->date));
        $this->transactionRepository->save($transaction);

        return $transaction;
    }
}
