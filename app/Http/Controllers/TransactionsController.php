<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransactionRequest;
use App\Http\Resources\AccountResource;
use App\Services\AccountService;
use App\Services\TransactionService;

class TransactionsController extends Controller
{
    private $transactionService;
    private $accountService;

    public function __construct(TransactionService $transactionService, AccountService $accountService)
    {
        $this->transactionService = $transactionService;
        $this->accountService = $accountService;
    }

    public function create(CreateTransactionRequest $request)
    {
        $data = $request->validated();

        $transaction = $this->transactionService->create($data);

        if (!$transaction || $transaction['errors']) {
            return response()->json($transaction, 404);
        }

        $account = $this->accountService->getById($data['account_id']);

        $this->itemResource = AccountResource::class;

        return response()->json($this->createResource($account), 201);
    }
}
