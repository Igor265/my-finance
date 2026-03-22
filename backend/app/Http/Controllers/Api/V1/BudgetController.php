<?php

namespace App\Http\Controllers\Api\V1;

use App\Contexts\Finance\Application\DTOs\CreateBudgetDTO;
use App\Contexts\Finance\Application\UseCases\CreateBudgetUseCase;
use App\Contexts\Finance\Domain\Repositories\BudgetRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreBudgetRequest;
use App\Http\Resources\Api\V1\BudgetResource;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public readonly CreateBudgetUseCase $createBudgetUseCase;

    public readonly BudgetRepository $budgetRepository;

    public function __construct(CreateBudgetUseCase $createBudgetUseCase, BudgetRepository $budgetRepository)
    {
        $this->createBudgetUseCase = $createBudgetUseCase;
        $this->budgetRepository = $budgetRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $budgets = $this->budgetRepository->findByUserId((string) $request->user()->id);

        return BudgetResource::collection($budgets);
    }

    public function store(StoreBudgetRequest $request)
    {
        $validated = $request->validated();
        $dto = new CreateBudgetDTO(
            (string) $request->user()->id,
            $validated['category_id'],
            $validated['maximum_amount'],
            $validated['alert_percentage'],
            $validated['start_date'],
            $validated['end_date'],
            $validated['currency'] ?? 'BRL',
        );
        $budget = $this->createBudgetUseCase->execute($dto);

        return (new BudgetResource($budget))->response()->setStatusCode(201);
    }

    public function show(string $id, Request $request)
    {
        $budget = $this->budgetRepository->findById($id);
        if (! $budget || $budget->userId !== (string) $request->user()->id) {
            return response()->json(['message' => 'Budget not found'], 404);
        }

        return new BudgetResource($budget);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
