<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ListNameUniqueOnAccount;

class StoreShoppingListRequest extends FormRequest
{
    public function authorize()
    {
        // Only allow if user has an active account
        return (bool) $this->user()->activeAccount;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', new ListNameUniqueOnAccount()],
        ];
    }
}
