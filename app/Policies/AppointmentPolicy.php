<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Redirect;

class AppointmentPolicy
{
    // APPOINTMENTS FOR BARBERS - APPOINTMENT CONTROLLER
    public function view(User $user, Appointment $appointment): Response
    {
        return $appointment->barber_id === $user->barber->id
            ? Response::allow()
            : Response::deny("You can't view other barbers' bookings.");
    }

    public function update(User $user, Appointment $appointment): Response
    {
        if ($appointment->barber_id != $user->barber->id) {
            return Response::deny("You can't edit other barbers' bookings.");
        } elseif ($appointment->app_start_time <= now()) {
            return Response::deny("You can't edit bookings from the past.");
        } elseif ($appointment->deleted_at) {
            return Response::deny("You can't edit cancelled bookings.");
        } else {
            return Response::allow();
        }
    }

    public function delete(User $user, Appointment $appointment): Response
    {
        if ($appointment->barber_id != $user->barber->id) {
            return Response::deny("You can't cancel other barbers' bookings.");
        } elseif ($appointment->app_start_time <= now()) {
            return Response::deny("You can't cancel bookings from the past.");
        } elseif ($appointment->deleted_at) {
            return Response::deny("You can't cancel already cancelled bookings.");
        } else {
            return Response::allow();
        }
    }

    public function isTimeOff(User $user, Appointment $appointment): bool
    {
        return $appointment->service_id == 1;
    }
}
