<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FreeEmailAddress implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $userWithThisEmail = User::where('email','=',$value)->get();
            if ($userWithThisEmail->count() == 1) {
                $user = $userWithThisEmail->first();
                if ($user->last_name != null) {
                    $fail('The selected email address is already taken! Please choose another one!');
                }
            }
        } catch (\Throwable $th) {
            $fail('The selected email address is already taken! Please choose another one!');
        }
    }
}
