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

    /**
     * List financial goals
     *
     * Returns all financial goals belonging to the authenticated user. Supports pagination via `per_page` query parameter (default: 15).
     */
    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 15);
        $goals = $this->financialGoalRepository->paginateByUserId((string) $request->user()->id, $perPage);

        return FinancialGoalResource::collection($goals);
    }

    /**
     * Create financial goal
     *
     * Creates a new savings goal with a target amount and deadline.
     */
    public function store(StoreFinancialGoalRequest $request)
    {
        $validated = $request->validated();
        $dto = new CreateFinancialGoalDTO(
            (string) $request->user()->id,
            $validated['name'],
            $validated['target_amount'],
            $validated['deadline'],
            $validated['currency'] ?? 'BRL',
        );
        $goal = $this->createFinancialGoalUseCase->execute($dto);

        return (new FinancialGoalResource($goal))->response()->setStatusCode(201);
    }

    /**
     * Get financial goal
     *
     * Returns a specific financial goal owned by the authenticated user.
     */
    public function show(string $id, Request $request)
    {
        $goal = $this->financialGoalRepository->findById($id);
        if (! $goal || $goal->userId !== (string) $request->user()->id) {
            return response()->json(['message' => __('messages.financial_goal_not_found')], 404);
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
