<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Account;
use App\Models\ShoppingItem;
use App\Models\ShoppingList;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShoppingListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testFetchLists()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create();
        $user->accounts()->attach($account);
        $user->last_active_account_id =  $account->id;
        $user->save();

        $lists = ShoppingList::factory()->count(3)->create([
            'account_id' => $account->id,
            'created_by' => $user->id,
        ]);

        foreach ($lists as $list) {
            ShoppingItem::factory()->count(2)->create([
                'shopping_list_id' => $list->id,
            ]);
        }

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

        $json = $res->json();

        foreach ($json['data'] as $list) {
            $this->assertIsArray($list['items']);          // items key exists
            $this->assertCount(2, $list['items']);         // 2 items per list

            // Check that each item has the expected keys
            foreach ($list['items'] as $item) {
                $this->assertArrayHasKey('id', $item);
                $this->assertArrayHasKey('name', $item);
                $this->assertArrayHasKey('is_completed', $item);
            }
        }
    }

    /** @test */
    public function testCreateList()
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
    public function testListNameUniqueWithinAccount()
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
