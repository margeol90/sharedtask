<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ShoppingItem;

class ShoppingItemPolicy
{
    public function view(User $user, ShoppingItem $item)
    {
        return $user->activeAccount->id === $item->shoppingList->account_id;
    }

    public function update(User $user, ShoppingItem $item)
    {
        return $user->activeAccount->id === $item->shoppingList->account_id;
    }

    public function delete(User $user, ShoppingItem $item)
    {
        return $user->activeAccount->id === $item->shoppingList->account_id;
    }
}
