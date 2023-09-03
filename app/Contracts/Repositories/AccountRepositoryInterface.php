<?php 

namespace App\Contracts\Repositories;

interface AccountRepositoryInterface
{
    public function getBalance($accountId);

    public function create($data);

    public function getById($accountId);

    public function getAll();

    public function updateBalance($accountId, $amount);
}