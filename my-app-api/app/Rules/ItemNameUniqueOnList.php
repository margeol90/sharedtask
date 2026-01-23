<?php

namespace App\Rules;

use Closure;
use App\Models\ShoppingItem;
use App\Models\ShoppingList;
use Illuminate\Contracts\Validation\ValidationRule;

class ItemNameUniqueOnList implements ValidationRule
{

    protected $shoppingListID;

    public function __construct($shoppingListID)
    {
        $this->shoppingListID = $shoppingListID;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $shoppingList = ShoppingList::find($this->shoppingListID);
        // Check if a shopping list with this name exists for any of the user's accounts
        if(ShoppingItem::where('shopping_list_id', $this->shoppingListID)
            ->where('name', $value)
            ->exists())
        {
            $fail('This item already exists in this list');
        }
    }
}
