<?php

namespace Tests\Feature;

use App\Models\Transaction;
use Tests\TestCase;
use App\Models\User;
use Tests\ResetsDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use ResetsDatabase;

    public function test_admin_can_save_payment(): void
    {
        Transaction::factory()->create();

        $user = Sanctum::actingAs(User::factory()->create(['is_admin'=>'1']));

        $transaction = Transaction::first();

        $response = $this->actingAs($user)->postJson('/api/v1/payments',[
            'transaction_id' => $transaction->id,
            'amount' => '100',
            'paid_on' => '2023-11-16'

        ]);

        $response->assertCreated();
    }

    public function test_user_can_not_save_payment(): void
    {

        Transaction::factory()->create();

        $user = Sanctum::actingAs(User::factory()->create(['is_admin'=>'0']));

        $transaction = Transaction::first();

        $response = $this->actingAs($user)->postJson('/api/v1/payments',[
            'transaction_id' => $transaction->id,
            'amount' => '100',
            'paid_on' => '2023-11-16'

        ]);

        $response->assertUnauthorized();
    }
}
