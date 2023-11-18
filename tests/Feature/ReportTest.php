<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Tests\ResetsDatabase;
use App\Models\Transaction;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportTest extends TestCase
{
    use ResetsDatabase;

    public function test_admin_can_generate_report(): void
    {
        Transaction::factory(100)->create();

        $user = Sanctum::actingAs(User::factory()->create(['is_admin'=>'1']));

        $transaction = Transaction::first();

        $response = $this->actingAs($user)->postJson('/api/v1/report',[
            'start_date' => '2021-01-01',
            'end_date' => '2021-12-30'

        ]);

        $response->assertOk();
    }

    public function test_user_cannot_generate_report(): void
    {
        Transaction::factory(100)->create();

        $user = Sanctum::actingAs(User::factory()->create(['is_admin'=>'0']));

        $transaction = Transaction::first();

        $response = $this->actingAs($user)->postJson('/api/v1/report',[
            'start_date' => '2021-01-01',
            'end_date' => '2021-12-30'

        ]);

        $response->assertUnauthorized();
    }
}
