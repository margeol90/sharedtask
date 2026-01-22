<?php

namespace App\Rules;

use Closure;
use App\Models\ShoppingItem;
use App\Models\ShoppingList;
use Illuminate\Contracts\Validation\ValidationRule;

class ItemNameUniqueOnList implements ValidationRule
{

    protected $shoppingList;

    public function __construct(ShoppingList $shoppingList)
    {
        $this->shoppingList = $shoppingList;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if a shopping list with this name exists for any of the user's accounts
        if($this->shoppingList->items()->where('name', $value)->exists())
        {
            $fail('This item already exists in this list');
        }
    }
}
