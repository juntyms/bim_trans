<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Tests\ResetsDatabase;
use App\Models\Transaction;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class TransactionTest extends TestCase
{
    use ResetsDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_admin_can_display_all_transaction_api_response(): void
    {
        Sanctum::actingAs(
            User::factory()->create(['is_admin'=>'1'])
        );

        $response = $this->getJson('/api/v1/transactions');

        $response->assertOK();
    }



    public function test_admin_can_save_transaction(): void
    {
        $user = Sanctum::actingAs(User::factory()->create(['is_admin'=>'1']));

        $response = $this->actingAs($user)->postJson('/api/v1/transactions',[
            'amount' => '500',
            'user_id' => $user->id,
            'due_on' => '2023-11-18',
            'vat' => '0.00',
            'is_vat' => '0'
        ]);

        $response->assertCreated();
    }

    public function test_admin_can_retrieve_transaction(): void
    {
        User::factory(1)->create();

        Transaction::factory(1)->create();

        $transaction = Transaction::first();

        Sanctum::actingAs(User::factory()->create(['is_admin'=>'1']));

        $response = $this->get('/api/v1/transactions',['transaction_id'=>$transaction->id]);

        $response->assertOk();
    }

    public function test_admin_can_update_transaction(): void
    {
        User::factory(10)->create();

        $user = User::where('is_admin',1)->first();

        Sanctum::actingAs($user);

        Transaction::factory(1)->create(['user_id'=>$user->id]);

        $transaction = Transaction::first();

        $response = $this->actingAs($user)->call('PATCH','/api/v1/transactions/'. $transaction->id,[
            'amount' => '100'
        ]);

        $response->assertOk();
    }

    public function test_admin_can_delete_transaction(): void
    {
        User::factory(10)->create();

        $user = User::where('is_admin',1)->first();

        Sanctum::actingAs($user);

        Transaction::factory(1)->create(['user_id'=>$user->id]);

        $transaction = Transaction::first();

        $response = $this->actingAs($user)->call('DELETE','/api/v1/transactions/'. $transaction->id);

        $response->assertOk();
    }

    public function test_user_can_only_view_their_transaction(): void
    {
        $user = User::factory()->create(['is_admin'=>'0']);

        Sanctum::actingAs(
            $user
        );

        Transaction::factory(10)->create(['user_id'=>$user->id]);

        $response = $this->get('/api/v1/transactions');

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data',10)
        );
    }
}
