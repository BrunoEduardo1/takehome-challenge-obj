<?php

namespace App\Services;

use App\Contracts\Repositories\AccountRepositoryInterface;
use App\Contracts\Repositories\TransactionRepositoryInterface;
use App\Models\Transaction;

class TransactionService
{
    CONST PIX_PAYMENT_METHOD = 'P';
    CONST CREDIT_CARD_PAYMENT_METHOD = 'C';
    CONST DEBIT_CARD_PAYMENT_METHOD = 'D';

    protected $accountRepository;
    protected $transactionRepository;

    private $feesPercentageByPaymentMethod = [
        self::PIX_PAYMENT_METHOD => 0,
        self::DEBIT_CARD_PAYMENT_METHOD => 3,
        self::CREDIT_CARD_PAYMENT_METHOD => 5,
    ];

    public function __construct(TransactionRepositoryInterface $transactionRepository, AccountRepositoryInterface $accountRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
    }

    /**
     * Create transaction
     * 
     * @param array $data [account_id, value]
     * @return Transaction|array 
     */
    public function create($data)
    {
        if (empty($data['account_id'])) {
            return ['errors' => ['account' => __('messages.invalid_account_id')]];
        }

        $account = $this->accountRepository->getById($data['account_id']);

        if (!$account) {
            return ['errors' => ['account' => __('messages.account_does_not_exist')]];
        }

        if ($data['value'] <= 0) {
            return ['errors' => ['transaction' => __('messages.invalid_transaction_value')]];
        }

        $amountAfterFees = $this->getAmountAfterFees($data['value'], $data['payment_method']);

        if ($amountAfterFees === false) {
            return ['errors' => ['transaction' => __('messages.invalid_payment_method')]];
        }

        if ($account->balance - $amountAfterFees < 0) {
            return ['errors' => ['transaction' => __('messages.insufficient_balance')]];
        }

        $this->accountRepository->updateBalance($data['account_id'], $amountAfterFees);

        $transaction = $this->transactionRepository->create([
            'account_id' => $data['account_id'],
            'amount' => $amountAfterFees,
        ]);

        return $transaction;
    }

    public function getAll()
    {
        $transactions = $this->transactionRepository->getAll();

        return $transactions;
    }

    public function getAmountAfterFees($value, $paymentMethod)
    {
        if (!isset($this->feesPercentageByPaymentMethod[$paymentMethod])) {
            return false;
        }

        if ($this->feesPercentageByPaymentMethod[$paymentMethod] === 0) {
            return $value;
        }

        $value = $value + ($value * ($this->feesPercentageByPaymentMethod[$paymentMethod] / 100));

        return $value;
    }
}
