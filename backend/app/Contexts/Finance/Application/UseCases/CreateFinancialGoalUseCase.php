<?php

namespace App\Contexts\Finance\Application\UseCases;

use App\Contexts\Finance\Application\DTOs\CreateFinancialGoalDTO;
use App\Contexts\Finance\Domain\Entities\FinancialGoal;
use App\Contexts\Finance\Domain\Repositories\FinancialGoalRepository;
use App\Contexts\Finance\Domain\ValueObjects\Money;
use Illuminate\Support\Str;

class CreateFinancialGoalUseCase
{
    readonly FinancialGoalRepository $financialGoalRepository;

    public function __construct(FinancialGoalRepository $financialGoalRepository)
    {
        $this->financialGoalRepository = $financialGoalRepository;
    }

    public function execute(CreateFinancialGoalDTO $dto): FinancialGoal
    {
        $goal = new FinancialGoal(
            (string) Str::uuid(),
            $dto->userId,
            $dto->name,
            Money::fromFloat($dto->targetAmount, $dto->currency),
            new Money(0, $dto->currency),
            new \DateTimeImmutable($dto->deadline),
        );
        $this->financialGoalRepository->save($goal);
        return $goal;
    }
}
