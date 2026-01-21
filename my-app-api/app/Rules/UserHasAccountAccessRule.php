<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\ValidationRule;

class UserHasAccountAccessRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $accountIds = Auth::user()
            ->accounts()
            ->pluck('accounts.id')
            ->toArray();

            if(!in_array($value, $accountIds))
            {
                fail('Your account doe not have access to this shopping list.');
            }
    }
}
