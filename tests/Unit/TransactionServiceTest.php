<?php

namespace Tests\Unit;

use App\Contracts\Repositories\AccountRepositoryInterface;
use App\Contracts\Repositories\TransactionRepositoryInterface;
use App\Models\Transaction;
use Tests\Mocks\AccountRepositoryMock;
use Tests\Mocks\TransactionRepositoryMock;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->bind(AccountRepositoryInterface::class, AccountRepositoryMock::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepositoryMock::class);

        $this->transactionService = $this->app->make('App\Services\TransactionService');
        $this->accountService = $this->app->make('App\Services\AccountService');
    }

    public function test_debit_transaction_with_sufficient_balance(): void
    {
        $data = [
            'payment_method' => 'D',
            'account_id' => 1,
            'value' => 50,
        ];

        $createdTransactionResult = $this->transactionService->create($data);

        $this->assertNotEmpty($createdTransactionResult);
        $this->assertInstanceOf(Transaction::class, $createdTransactionResult);
        $this->assertEquals(
            $this->transactionService->getAmountAfterFees($data['value'], $data['payment_method']),
            $createdTransactionResult->amount
        );
    }

    public function test_credit_transaction_with_sufficient_balance(): void
    {
        $data = [
            'payment_method' => 'C',
            'account_id' => 1,
            'value' => 100,
        ];

        $createdTransactionResult = $this->transactionService->create($data);

        $this->assertNotEmpty($createdTransactionResult);
        $this->assertInstanceOf(Transaction::class, $createdTransactionResult);
        $this->assertEquals(
            $this->transactionService->getAmountAfterFees($data['value'], $data['payment_method']),
            $createdTransactionResult->amount
        );
    }

    public function test_pix_transaction_with_sufficient_balance(): void
    {
        $data = [
            'payment_method' => 'P',
            'account_id' => 1,
            'value' => 75,
        ];

        $createdTransactionResult = $this->transactionService->create($data);

        $this->assertNotEmpty($createdTransactionResult);
        $this->assertInstanceOf(Transaction::class, $createdTransactionResult);
        $this->assertEquals(
            $this->transactionService->getAmountAfterFees($data['value'], $data['payment_method']),
            $createdTransactionResult->amount
        );
    }

    public function test_failure_transaction_with_insufficient_balance(): void
    {
        $data = [
            'payment_method' => 'P',
            'account_id' => 1,
            'value' => 2000,
        ];

        $createdTransactionResult = $this->transactionService->create($data);

        $this->assertNotEmpty($createdTransactionResult);
        $this->assertEquals(__('messages.insufficient_balance'), $createdTransactionResult['errors']['transaction']);
    }

    public function test_failure_transaction_with_invalid_account(): void
    {
        $data = [
            'payment_method' => 'P',
            'account_id' => 90,
            'value' => 500,
        ];

        $createdTransactionResult = $this->transactionService->create($data);

        $this->assertNotEmpty($createdTransactionResult);
        $this->assertEquals(__('messages.account_does_not_exist'), $createdTransactionResult['errors']['account']);
    }

}
