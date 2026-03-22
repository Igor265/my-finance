<?php

namespace App\Contexts\Finance\Application\UseCases;

use App\Contexts\Finance\Application\DTOs\CreateBudgetDTO;
use App\Contexts\Finance\Domain\Entities\Budget;
use App\Contexts\Finance\Domain\Repositories\BudgetRepository;
use App\Contexts\Finance\Domain\ValueObjects\BudgetLimit;
use App\Contexts\Finance\Domain\ValueObjects\Money;
use App\Contexts\Finance\Domain\ValueObjects\Period;
use Illuminate\Support\Str;

class CreateBudgetUseCase
{
    public readonly BudgetRepository $budgetRepository;

    public function __construct(BudgetRepository $budgetRepository)
    {
        $this->budgetRepository = $budgetRepository;
    }

    public function execute(CreateBudgetDTO $dto): Budget
    {
        $budget = new Budget(
            (string) Str::uuid(),
            $dto->userId,
            $dto->categoryId,
            new BudgetLimit(Money::fromFloat($dto->maximumAmount, $dto->currency), $dto->alertPercentage),
            Period::fromStrings($dto->startDate, $dto->endDate),
        );
        $this->budgetRepository->save($budget);

        return $budget;
    }
}
