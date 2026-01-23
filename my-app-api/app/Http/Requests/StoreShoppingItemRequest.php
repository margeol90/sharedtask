<?php

namespace App\Http\Requests;

use App\Rules\ItemNameUniqueOnList;
use Illuminate\Foundation\Http\FormRequest;

class StoreShoppingItemRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Policies will handle access
    }

    public function rules()
    {
        $shoppingList = $this->route('shoppingList');

        $shoppingListId = $shoppingList instanceof \App\Models\ShoppingList
            ? $shoppingList->id
            : (int) $shoppingList;

        return [
            'name' => ['required', 'string', 'max:255', new ItemNameUniqueOnList($shoppingListId)],
        ];
    }
}
