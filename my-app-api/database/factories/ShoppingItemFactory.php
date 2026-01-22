<?php

namespace Database\Factories;

use App\Models\ShoppingItem;
use App\Models\ShoppingList;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShoppingItemFactory extends Factory
{
    protected $model = ShoppingItem::class;

    public function definition()
    {
        return [
            'shopping_list_id' => ShoppingList::factory(),
            'name' => $this->faker->word(),
            'is_completed' => false,
        ];
    }
}
