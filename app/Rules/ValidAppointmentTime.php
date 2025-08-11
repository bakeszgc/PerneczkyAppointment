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

            // appointment start can't be before the opening and after the closing hour
            if ($hours<10 || $hours >= Appointment::closingHour($app_start_time)) {
                $fail('The selected date is out of! Please choose another one!');
            }

            // appointment can only start on every 15 mins
            if (!in_array($minutes,[0,15,30,45]) || $seconds != 0) {
                $fail('The selected date is not available! Please choose another one!');
            }

            if ($app_start_time < now()) {
                $fail('The selected date is in the past! Please choose another one!');
            }

        } catch (\Exception $e) {
            $fail('The selected date is not available! Please choose another one!');
        }
    }
}
