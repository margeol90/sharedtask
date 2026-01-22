<?php

namespace App\Rules;

use Closure;
use App\Models\ShoppingList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\ValidationRule;

class ListNameUniqueOnAccount implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if a shopping list with this name exists for any of the user's accounts
        $existing = ShoppingList::where('name', $value)
            ->whereIn('account_id', Auth::user()->accounts()->pluck('accounts.id'))
            ->exists();

        if($existing)
        {
            $fail('This name has already been used before.');
        }
    }
}
