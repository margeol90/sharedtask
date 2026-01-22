<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Account;
use App\Models\ShoppingList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_user_can_fetch_their_shopping_lists()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create();
        $user->accounts()->attach($account);
        $user->last_active_account_id =  $account->id;
        $user->save();

        ShoppingList::factory()->count(3)->create([
            'account_id' => $account->id,
            'created_by' => $user->id,
        ]);

        $res = $this->actingAs($user, 'sanctum')
            ->getJson('/api/shopping-lists');

        // Assert HTTP 200 OK
        $res->assertStatus(200);

        // Assert the "data" key exists and has 3 lists
        $res->assertJsonCount(3, 'data');

        // Assert each list has the keys we expect
        $res->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'items']
            ]
        ]);

    }

    /** @test */
    public function test_user_can_create_a_shopping_list()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create();
        $user->accounts()->attach($account);
        $user->last_active_account_id = $account->id;
        $user->save();

        $payload = ['name' => 'Groceries'];

        $res = $this->actingAs($user, 'sanctum')
            ->postJson('/api/shopping-lists', $payload)
            ->assertStatus(201)
            ->assertJsonFragment(['name' => 'Groceries']);
    }

    /** @test */
    public function test_shopping_list_name_must_be_unique_within_account()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create();
        $user->accounts()->attach($account);
        $user->last_active_account_id = $account->id;
        $user->save();

        ShoppingList::factory()->create([
            'account_id' => $account->id,
            'created_by' => $user->id,
            'name' => 'Groceries',
        ]);

        $payload = ['name' => 'Groceries'];

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/shopping-lists', $payload)
            ->assertStatus(422) // validation error
            ->assertJsonValidationErrors(['name']);
    }
}
