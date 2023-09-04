<?php

namespace App\Contracts\Repositories;

interface TransactionRepositoryInterface
{
    public function create($data);

    public function getById($transactionId);

    public function getAll();
}
