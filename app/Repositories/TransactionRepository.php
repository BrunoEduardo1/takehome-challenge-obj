<?php

namespace App\Repositories;

use App\Contracts\Repositories\TransactionRepositoryInterface;
use App\Models\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    protected $model = Transaction::class;
    protected $perPage = 15;

    public function getAll()
    {
        return $this->model::paginate($this->perPage);
    }

    public function getById($transactionId)
    {
        return $this->model::find($transactionId);
    }

    public function create($data)
    {
        return $this->model::create($data);
    }
}
