<?php

namespace Tests\Mocks;

use App\Contracts\Repositories\TransactionRepositoryInterface;
use App\Models\Transaction;

class TransactionRepositoryMock implements TransactionRepositoryInterface
{
    protected $model = Transaction::class;
    protected $perPage = 15;

    public function __construct()
    {
        $this->initializeData();
    }

    private function initializeData()
    {
        $this->data = [
            ['id' => 1, 'account_id' => 1, 'payment_method' => 'D','amount' => 100],
            ['id' => 2, 'account_id' => 1, 'payment_method' => 'C','amount' => 100],
        ];
    }

    public function getAll()
    {
        return array_map(function ($item) {
            return new $this->model($item);
        }, $this->data);
    }

    public function getById($transactionId)
    {
        foreach ($this->data as $item) {
            if ($item['id'] == $transactionId) {
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
}
