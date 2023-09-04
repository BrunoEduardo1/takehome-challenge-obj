<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountRequest;
use App\Http\Resources\AccountResource;
use Illuminate\Http\Request;
use App\Services\AccountService;
use Symfony\Component\HttpFoundation\Response;

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
            return response()->json($account, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json($this->createResource($account), Response::HTTP_CREATED);
    }

    public function getById(Request $request)
    {
        $id = $request->id;

        $account = $this->accountService->getById($id);

        if (!$account || !empty($account['errors'])) {
            return response($account, Response::HTTP_NOT_FOUND);
        }

        return response()->json($this->createResource($account));
    }
}
