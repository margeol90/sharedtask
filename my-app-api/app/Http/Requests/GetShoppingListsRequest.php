<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use App\Rules\UserHasAccountAccessRule;
use Illuminate\Foundation\Http\FormRequest;

class GetShoppingListsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "account_id" => [
                'required',
                'int',
                'exists:shopping_lists',
                new UserHasAccountAccessRule(Auth::user()->id)
            ]
        ];
    }
}
