<?php

namespace App\Rules;

use App\Models\Appointment;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidAppointmentTime implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $app_start_time = Carbon::parse($value);
            $hours = $app_start_time->hour;
            $minutes = $app_start_time->minute;
            $seconds = $app_start_time->second;

            // nyitás előtti és zárás utáni időpont sem lehet
            if ($hours<10 || $hours >= Appointment::closingHour($app_start_time)) {
                $fail('The selected date is not available! Please choose another one!');
            }

            // csak negyed órás app_start_timeok lehetnek
            if (!in_array($minutes,[0,15,30,45]) || $seconds != 0) {
                $fail('The selected date is not available! Please choose another one!');
            }
        } catch (\Exception $e) {
            $fail('The selected date is not available! Please choose another one!');
        }
    }
}
