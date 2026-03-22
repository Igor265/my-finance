<?php

namespace App\Http\Controllers\Api\V1;

use App\Contexts\Finance\Application\DTOs\CreateFinancialGoalDTO;
use App\Contexts\Finance\Application\UseCases\CreateFinancialGoalUseCase;
use App\Contexts\Finance\Domain\Repositories\FinancialGoalRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreFinancialGoalRequest;
use App\Http\Resources\Api\V1\FinancialGoalResource;
use Illuminate\Http\Request;

class FinancialGoalController extends Controller
{
    public readonly CreateFinancialGoalUseCase $createFinancialGoalUseCase;

    public readonly FinancialGoalRepository $financialGoalRepository;

    public function __construct(CreateFinancialGoalUseCase $createFinancialGoalUseCase, FinancialGoalRepository $financialGoalRepository)
    {
        $this->createFinancialGoalUseCase = $createFinancialGoalUseCase;
        $this->financialGoalRepository = $financialGoalRepository;
    }

    public function index(Request $request)
    {
        $goals = $this->financialGoalRepository->findByUserId($request->user()->id);

        return FinancialGoalResource::collection($goals);
    }

    public function store(StoreFinancialGoalRequest $request)
    {
        $validated = $request->validated();
        $dto = new CreateFinancialGoalDTO(
            $request->user()->id,
            $validated['name'],
            $validated['target_amount'],
            $validated['deadline'],
            $validated['currency'] ?? 'BRL',
        );
        $goal = $this->createFinancialGoalUseCase->execute($dto);

        return (new FinancialGoalResource($goal))->response()->setStatusCode(201);
    }

    public function show(string $id, Request $request)
    {
        $goal = $this->financialGoalRepository->findById($id);
        if (! $goal || $goal->userId !== (string) $request->user()->id) {
            return response()->json(['message' => 'Financial goal not found'], 404);
        }

        return new FinancialGoalResource($goal);
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
