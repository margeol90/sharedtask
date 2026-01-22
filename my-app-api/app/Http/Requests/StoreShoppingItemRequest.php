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
        return [
            'name' => ['required', 'string', 'max:255', new ItemNameUniqueOnList($this->route('shoppingList'))],
        ];
    }


}
