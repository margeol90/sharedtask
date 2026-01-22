<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ShoppingList;

class ShoppingListPolicy
{
    public function view(User $user, ShoppingList $shoppingList)
    {
        return $user->activeAccount->id === $shoppingList->account_id;
    }

    public function create(User $user)
    {
        return (bool) $user->activeAccount;
    }

    public function update(User $user, ShoppingList $shoppingList)
    {
        return $user->activeAccount->id === $shoppingList->account_id;
    }

    public function delete(User $user, ShoppingList $shoppingList)
    {
        return $user->activeAccount->id === $shoppingList->account_id;
    }
}
