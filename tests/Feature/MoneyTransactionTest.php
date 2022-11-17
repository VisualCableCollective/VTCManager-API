<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MoneyTransactionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_create_and_show_transactions()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->actingAs($user1);

        $this->postJson('/api/money-transactions', [
            'receiver_id' => $user2->id,
            'amount' => 10.99
        ])->assertSuccessful();

        $this->assertTrue(User::find($user2->id)->bank_balance == round($user2->bank_balance + 10.99, 2));

        $this->assertTrue(User::find($user1->id)->bank_balance == round($user1->bank_balance - 10.99, 2));

        $this->actingAs($user2);

        $this->postJson('/api/money-transactions', [
            'receiver_id' => $user1->id,
            'amount' => 12.99
        ])->assertSuccessful();

        $response = $this->get('/api/money-transactions');

        $response->assertSuccessful();

        $jsonData = $response->json();

        // ensure correct order
        assert($jsonData[0]['receiver_id'] == $user2->id);
        assert($jsonData[1]['receiver_id'] == $user1->id);
    }
}
