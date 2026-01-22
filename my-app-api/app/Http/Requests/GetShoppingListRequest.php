<?php

namespace App\Http\Requests;

use App\Models\ShoppingList;
use App\Rules\UserHasAccountAccessRule;
use Illuminate\Foundation\Http\FormRequest;

class GetShoppingListRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "id" => [
                'required',
                'int',
                'exists:shopping_lists'
            ],
            "account_id" => [
                'required',
                'int',
                'exists:accounts',
                new UserHasAccountAccessRule()
            ]
        ];
    }

    protected function prepareForValidation(): void {
        $listAccount = ShoppingList::show($this->route()->parameter('id'));

        $this->merge([
            'id' => $this->route()->parameter('id'),
            'account_id' => $listAccount->account_id
        ]);
    }
}
