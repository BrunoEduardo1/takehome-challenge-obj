<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionsControllerTest extends TestCase
{
    use RefreshDatabase;

    private $accountBalance = 500;

    private $feesPercentageByPaymentMethod = [
        'P' => 0,
        'D' => 3,
        'C' => 5,
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->post(route('accounts.create'), [
            'account_id' => 1,
            'value' => $this->accountBalance,
        ]);
    }

    public function test_debit_transaction_with_sufficient_balance(): void
    {
        $data = [
            'payment_method' => 'D',
            'account_id' => 1,
            'value' => 50,
        ];

        $valueAfterFees = ($data['value'] + ($data['value'] * ($this->feesPercentageByPaymentMethod[$data['payment_method']] / 100)));

        $response = $this->post(route('transactions.create'), $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'account_id' => $data['account_id'],
                'balance' => $this->accountBalance - $valueAfterFees,
            ]);
    }

    public function test_credit_transaction_with_sufficient_balance(): void
    {
        $data = [
            'payment_method' => 'C',
            'account_id' => 1,
            'value' => 100,
        ];

        $valueAfterFees = ($data['value'] + ($data['value'] * ($this->feesPercentageByPaymentMethod[$data['payment_method']] / 100)));

        $response = $this->post(route('transactions.create'), $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'account_id' => $data['account_id'],
                'balance' => $this->accountBalance - $valueAfterFees,
            ]);
    }

    public function test_pix_transaction_with_sufficient_balance(): void
    {
        $data = [
            'payment_method' => 'P',
            'account_id' => 1,
            'value' => 75,
        ];

        $valueAfterFees = ($data['value'] + ($data['value'] * ($this->feesPercentageByPaymentMethod[$data['payment_method']] / 100)));

        $response = $this->post(route('transactions.create'), $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'account_id' => $data['account_id'],
                'balance' => $this->accountBalance - $valueAfterFees,
            ]);
    }

    public function test_failure_transaction_with_insufficient_balance(): void
    {
        $data = [
            'payment_method' => 'P',
            'account_id' => 1,
            'value' => 501,
        ];

        $response = $this->post(route('transactions.create'), $data);

        $response
            ->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'transaction' => __('messages.insufficient_balance')
                ],
            ]);
    }

    public function test_failure_transaction_with_invalid_account(): void
    {
        $data = [
            'payment_method' => 'P',
            'account_id' => 90,
            'value' => 1,
        ];

        $response = $this->post(route('transactions.create'), $data);

        $response
            ->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'account' => __('messages.account_does_not_exist')
                ],
            ]);
    }
}
