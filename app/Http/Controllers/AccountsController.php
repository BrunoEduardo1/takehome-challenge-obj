<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountRequest;
use App\Http\Resources\AccountResource;
use Illuminate\Http\Request;
use App\Services\AccountService;

class AccountsController extends Controller
{
    private $accountService;
    protected $itemResource = AccountResource::class;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function create(CreateAccountRequest $request)
    {
        $data = $request->validated();

        $account = $this->accountService->create($data);

        if (!$account || !empty($account['errors'])) {
            return response()->json($account, 422);
        }

        return response()->json($this->createResource($account), 201);
    }

    public function getById(Request $request)
    {
        $id = $request->id;

        $account = $this->accountService->getById($id);

        if (!$account || !empty($account['errors'])) {
            return response($account, 404);
        }

        return response()->json($this->createResource($account));
    }
}
