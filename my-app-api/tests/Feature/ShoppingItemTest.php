<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Account;
use App\Models\ShoppingItem;
use App\Models\ShoppingList;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShoppingItemTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $account;
    protected $list;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->account = Account::factory()->create();
        $this->user->accounts()->attach($this->account);
        $this->user->last_active_account_id = $this->account->id;
        $this->user->save();

        $this->list = ShoppingList::factory()->create([
            'account_id' => $this->account->id,
            'created_by' => $this->user->id,
        ]);
    }

    public function testFetchItemsOfList()
    {
        $items = ShoppingItem::factory()->count(3)->create([
            'shopping_list_id' => $this->list->id
        ]);

        $res = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/shopping-lists/{$this->list->id}/items");
        $res->assertStatus(200);
        $res->assertJsonCount(3, 'data');
    }

    public function testUserCannotViewShoppingListFromAnotherAccount()
    {
        $foreignUser = User::factory()->create();
        $foreignAccount = Account::factory()->create();
        $foreignUser->accounts()->attach($foreignAccount);
        $foreignUser->last_active_account_id = $foreignAccount->id;
        $this->user->save();

        $foreignList = ShoppingList::factory()->create([
            'account_id' => $foreignAccount->id,
            'created_by' => $foreignUser->id,
        ]);

        $res = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/shopping-list/{$foreignList->id}")
            ->assertStatus(403);
    }

    public function testUserCanCreateItem()
    {
        $payload = ['name' => 'Buy milk'];

        $res = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/shopping-lists/{$this->list->id}/items", $payload);

        $res->assertStatus(201)
            ->assertJsonPath('data.name', 'Buy milk');

        $this->assertDatabaseHas('shopping_items', [
            'name' => 'Buy milk',
            'shopping_list_id' => $this->list->id
        ]);
       
    }

    public function testUserCanUpdateItem()
    {
        $item = ShoppingItem::factory()->create(['shopping_list_id' => $this->list->id]);

        $payload = ['name' => 'Buy eggs'];

        $res = $this->actingAs($this->user, 'sanctum')
            ->patchJson("/api/shopping-lists/{$this->list->id}/items/{$item->id}", $payload);

        $res->assertStatus(200)
            ->assertJsonPath('data.name', 'Buy eggs');

        $this->assertDatabaseHas('shopping_items', [
            'id' => $item->id,
            'name' => 'Buy eggs'
        ]);
    }

    public function testUserCanToggleItem()
    {
        $item = ShoppingItem::factory()->create([
            'shopping_list_id' => $this->list->id,
            'is_completed' => false
        ]);

        $res = $this->actingAs($this->user, 'sanctum')
            ->patchJson("/api/shopping-lists/{$this->list->id}/items/{$item->id}/toggle");
        $res->assertStatus(200)
            ->assertJsonPath('data.is_completed', true);
    }

    public function testUserCannotToggleItemFromAnotherAccount()
    {
        $foreignUser = User::factory()->create();
        $foreignAccount = Account::factory()->create();
        $foreignUser->accounts()->attach($foreignAccount);
        $foreignUser->last_active_account_id = $foreignAccount->id;
        $this->user->save();

        $foreignList = ShoppingList::factory()->create([
            'account_id' => $foreignAccount->id,
            'created_by' => $foreignUser->id,
        ]);

        $foreignItem = ShoppingItem::factory()->create([
            'shopping_list_id' => $foreignList->id,
        ]);

        $this->actingAs($this->user, 'sanctum')
            ->patchJson("/api/shopping-lists/{$foreignList->id}/items/{$foreignItem->id}/toggle")
            ->assertStatus(403);
    }


    public function testUserCanDeleteItem()
    {
        $item = ShoppingItem::factory()->create(['shopping_list_id' => $this->list->id]);

        $res = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/shopping-lists/{$this->list->id}/items/{$item->id}");

            $res->assertNoContent();
        $this->assertDatabaseMissing('shopping_items', ['id' => $item->id]);
    }


}
