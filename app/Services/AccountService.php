<?php

namespace App\Services;

use App\Contracts\Repositories\AccountRepositoryInterface;

class AccountService
{
    protected $accountRepository;

    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function create($data)
    {
        if (empty($data['account_id'])) {
            return ['errors' => ['account' => __('messages.invalid_account_id')]];
        }

        $accountExists = $this->accountRepository->getById($data['account_id']);

        if ($accountExists) {
            return ['errors' => ['account' => __('messages.account_already_exists')]];
        }

        if ($data['value'] < 0) {
            return ['errors' => ['account' => __('messages.account_balance_cannot_be_negative')]];
        }

        return $this->accountRepository->create([
            'id' => $data['account_id'],
            'balance' => $data['value'],
        ]);
    }

    public function getAll()
    {
        $accounts = $this->accountRepository->getAll();

        return $accounts;
    }

    public function getById($accountId)
    {
        $account = $this->accountRepository->getById($accountId);

        if (!$account) {
            return ['errors' => ['account' => __('messages.account_not_found')]];
        }

        return $account;
    }
}
