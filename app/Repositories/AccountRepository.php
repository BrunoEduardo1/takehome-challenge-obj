<?php

namespace App\Repositories;

use App\Contracts\Repositories\AccountRepositoryInterface;
use App\Models\Account;

class AccountRepository implements AccountRepositoryInterface
{
    protected $model = Account::class;
    protected $perPage = 15;

    public function getAll()
    {
        return $this->model::paginate($this->perPage);
    }

    public function getById($accountId)
    {
        return $this->model::find($accountId);
    }

    public function getBalance($accountId)
    {
        $account = $this->getById($accountId);

        return $account->balance;
    }

    public function create($data)
    {
        return $this->model::create($data);
    }

    public function updateBalance($accountId, $newBalance)
    {
        $account = $this->getById($accountId);

        if (!$account) {
            return false;
        }

        $account->update(['balance' => $newBalance]);

        return $account;
    }
}
