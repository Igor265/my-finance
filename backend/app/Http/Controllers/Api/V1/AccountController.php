<?php

namespace App\Http\Controllers\Api\V1;

use App\Contexts\Finance\Application\DTOs\CreateAccountDTO;
use App\Contexts\Finance\Application\UseCases\CreateAccountUseCase;
use App\Contexts\Finance\Domain\Repositories\AccountRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreAccountRequest;
use App\Http\Resources\Api\V1\AccountResource;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public readonly CreateAccountUseCase $createAccountUseCase;

    public readonly AccountRepository $accountRepository;

    public function __construct(CreateAccountUseCase $createAccountUseCase, AccountRepository $accountRepository)
    {
        $this->createAccountUseCase = $createAccountUseCase;
        $this->accountRepository = $accountRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $accounts = $this->accountRepository->findByUserId($userId);

        return AccountResource::collection($accounts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request)
    {
        $validated = $request->validated();
        $dto = new CreateAccountDTO(
            $request->user()->id,
            $validated['name'], $validated['type'],
            $validated['initial_amount'] ?? 0.0, $validated['currency'] ?? 'BRL'
        );
        $account = $this->createAccountUseCase->execute($dto);

        return (new AccountResource($account))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $account = $this->accountRepository->findById($id);
        if (! $account || $account->userId !== (string) $request->user()->id) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        return new AccountResource($account);
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
