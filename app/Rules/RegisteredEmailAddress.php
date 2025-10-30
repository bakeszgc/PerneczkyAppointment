<?php

namespace App\Rules;

use Closure;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class RegisteredEmailAddress implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $user = User::whereEmail($value)->first();
            if (!isset($user->last_name)) {
                $fail("We don't have any accounts registered using this email address.");
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
