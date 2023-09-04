<?php

namespace Tests\Unit;

use App\Contracts\Repositories\AccountRepositoryInterface;
use App\Models\Account;
use Tests\Mocks\AccountRepositoryMock;

use Tests\TestCase;

class AccountServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->bind(AccountRepositoryInterface::class, AccountRepositoryMock::class);

        $this->accountService = $this->app->make('App\Services\AccountService');
    }

    public function test_create_account(): void {
        $createdAccountResult = $this->accountService->create([
            'account_id' => 2001,
            'value' => 500,
        ]);

        $this->assertNotEmpty($createdAccountResult);
        $this->assertInstanceOf(Account::class, $createdAccountResult);
        $this->assertEquals(2001, $createdAccountResult->id);
    }

    public function test_failure_create_account_with_negative_balance(): void {
        $createdAccountResult = $this->accountService->create([
            'account_id' => 2001,
            'value' => -1,
        ]);

        $this->assertNotEmpty($createdAccountResult);
        $this->assertEquals(__('messages.account_balance_cannot_be_negative'), $createdAccountResult['errors']['account']);
    }

    public function test_failure_create_account_with_repeated_account_id(): void {
        $createdAccountResult = $this->accountService->create([
            'account_id' => 1,
            'value' => 500,
        ]);

        $this->assertNotEmpty($createdAccountResult);
        $this->assertEquals(__('messages.account_already_exists'), $createdAccountResult['errors']['account']);
    }

    public function test_list_accounts(): void
    {
        $accountsArray = $this->accountService->getAll();

        $this->assertIsArray($accountsArray);
        $this->assertNotEmpty($accountsArray);

        foreach ($accountsArray as $account) {
            $this->assertInstanceOf(Account::class, $account);
        }
    }

    public function test_get_account_by_id(): void
    {
        $accountId = 2;
        $account = $this->accountService->getById($accountId);

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals($accountId, $account->id);
    }
}
