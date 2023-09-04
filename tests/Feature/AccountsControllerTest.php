<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountsControllerTest extends TestCase
{    
    use RefreshDatabase;

    public function test_create_account(): void
    {
        $data = [
            'account_id' => 1,
            'value' => 1000,
        ];

        $response = $this
            ->post(route('accounts.create'), $data);

        $response
            ->assertStatus(201)
            ->assertJson([
                'account_id' => $data['account_id'],
                'balance' => $data['value'],
            ]);
    }

    public function test_get_account_by_id(): void
    {
        $data = [
            'account_id' => 1,
            'value' => 1000,
        ];

        $this->post(route('accounts.create'), $data);

        $response = $this->get(route('accounts.getById', ['id' => $data['account_id']]));

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'account_id',
                'balance',
            ])
            ->assertJson([
                'account_id' => $data['account_id'],
            ]);
    }

    public function test_failure_create_account_with_invalid_data(): void
    {
        $data = [
            'account_id' => 1001,
            'value' => -1,
        ];

        $response = $this
            ->post(route('accounts.create'), $data);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors',
            ]);
    }
}
