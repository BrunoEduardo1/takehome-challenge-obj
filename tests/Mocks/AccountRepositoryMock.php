<?php

namespace Tests\Mocks;

use App\Contracts\Repositories\AccountRepositoryInterface;
use App\Models\Account;

class AccountRepositoryMock implements AccountRepositoryInterface
{
    protected $model = Account::class;
    protected $perPage = 15;

    public function __construct()
    {
        $this->initializeData();
    }

    private function initializeData()
    {
        $this->data = [
            ['id' => 1, 'balance' => 500],
            ['id' => 2, 'balance' => 120],
        ];
    }

    public function getAll()
    {
        return array_map(function ($item) {
            return new $this->model($item);
        }, $this->data);
    }

    public function getById($accountId)
    {
        foreach ($this->data as $item) {
            if ($item['id'] == $accountId) {
                return new $this->model($item);
            }
        }

        return null;
    }

    public function create($data)
    {
        $this->data[] = $data;

        return new $this->model($data);
    }

    public function updateBalance($accountId, $newBalance)
    {
        foreach ($this->data as $account) {
            if ($account['id'] === $accountId) {
                $account['balance'] = $newBalance;
                return new $this->model($account);
            }
        }
        return null;
    }

    public function getBalance($accountId)
    {
        $account = $this->getById($accountId);

        return $account->balance;
    }
}
