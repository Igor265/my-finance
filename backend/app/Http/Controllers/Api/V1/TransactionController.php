<?php

namespace App\Http\Controllers\Api\V1;

use App\Contexts\Finance\Application\DTOs\CreateTransactionDTO;
use App\Contexts\Finance\Application\UseCases\CreateTransactionUseCase;
use App\Contexts\Finance\Domain\Repositories\AccountRepository;
use App\Contexts\Finance\Domain\Repositories\TransactionRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreTransactionRequest;
use App\Http\Resources\Api\V1\TransactionResource;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public readonly CreateTransactionUseCase $createTransactionUseCase;

    public readonly TransactionRepository $transactionRepository;

    public readonly AccountRepository $accountRepository;

    public function __construct(CreateTransactionUseCase $createTransactionUseCase, TransactionRepository $transactionRepository, AccountRepository $accountRepository)
    {
        $this->createTransactionUseCase = $createTransactionUseCase;
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
    }

    /**
     * List transactions by account
     *
     * Returns all transactions for a specific account owned by the authenticated user.
     */
    public function index(string $accountId, Request $request)
    {
        $account = $this->accountRepository->findById($accountId);
        if (! $account || $account->userId !== (string) $request->user()->id) {
            return response()->json(['message' => __('messages.transaction_not_found')], 404);
        }
        $transactions = $this->transactionRepository->findByAccountId($accountId);

        return TransactionResource::collection($transactions);
    }

    /**
     * Create transaction
     *
     * Creates a new transaction for an account owned by the authenticated user.
     */
    public function store(StoreTransactionRequest $request)
    {
        $validated = $request->validated();
        $account = $this->accountRepository->findById($validated['account_id']);
        if (! $account || $account->userId !== (string) $request->user()->id) {
            return response()->json(['message' => __('messages.forbidden')], 403);
        }
        $dto = new CreateTransactionDTO(
            $validated['account_id'],
            $validated['amount'],
            $validated['type'],
            $validated['description'],
            $validated['date'],
            $validated['currency'] ?? 'BRL',
            $validated['category_id'] ?? null,
        );
        $transaction = $this->createTransactionUseCase->execute($dto);

        return (new TransactionResource($transaction))->response()->setStatusCode(201);
    }

    /**
     * Get transaction
     *
     * Returns a specific transaction. Returns 404 if not found or not owned by the authenticated user.
     */
    public function show(string $id, Request $request)
    {
        $transaction = $this->transactionRepository->findById($id);
        if (! $transaction) {
            return response()->json(['message' => __('messages.transaction_not_found')], 404);
        }
        $account = $this->accountRepository->findById($transaction->accountId);
        if (! $account || $account->userId !== (string) $request->user()->id) {
            return response()->json(['message' => __('messages.transaction_not_found')], 404);
        }

        return new TransactionResource($transaction);
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
