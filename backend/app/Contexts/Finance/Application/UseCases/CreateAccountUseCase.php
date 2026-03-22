<?php

namespace App\Contexts\Finance\Application\UseCases;

use App\Contexts\Finance\Application\DTOs\CreateAccountDTO;
use App\Contexts\Finance\Domain\Entities\Account;
use App\Contexts\Finance\Domain\Repositories\AccountRepository;
use App\Contexts\Finance\Domain\ValueObjects\AccountType;
use App\Contexts\Finance\Domain\ValueObjects\Money;
use Illuminate\Support\Str;

class CreateAccountUseCase
{
    public readonly AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function execute(CreateAccountDTO $dto): Account
    {
        $account = new Account((string) Str::uuid(), $dto->userId, $dto->name, Money::fromFloat($dto->initialAmount, $dto->currency), AccountType::from($dto->type));
        $this->accountRepository->save($account);

        return $account;
    }
}
