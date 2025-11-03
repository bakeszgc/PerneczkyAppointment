<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Redirect;

class AppointmentPolicy
{
    // FOR BARBERS - APPOINTMENT & TIMEOFF CONTROLLER
    public function view(User $user, Appointment $appointment): Response
    {
        return $appointment->barber_id === $user->barber->id
            ? Response::allow()
            : Response::deny("You can't view other barbers' " . $appointment->getType() . "s.");
    }

    public function update(User $user, Appointment $appointment): Response
    {
        if ($appointment->barber_id != $user->barber->id) {
            return Response::deny("You can't edit other barbers' " . $appointment->getType() . "s.");
        } elseif ($appointment->app_start_time <= now()) {
            return Response::deny("You can't edit " . $appointment->getType() . "s from the past.");
        } elseif ($appointment->deleted_at) {
            return Response::deny("You can't edit cancelled " . $appointment->getType() . "s.");
        } else {
            return Response::allow();
        }
    }

    public function delete(User $user, Appointment $appointment): Response
    {
        if ($appointment->barber_id != $user->barber->id) {
            return Response::deny("You can't cancel other barbers' " . $appointment->getType() . "s.");
        } elseif ($appointment->app_start_time <= now()) {
            return Response::deny("You can't cancel " . $appointment->getType() . "s from the past.");
        } elseif ($appointment->deleted_at) {
            return Response::deny("You can't cancel already cancelled " . $appointment->getType() . "s.");
        } else {
            return Response::allow();
        }
    }

    // FOR USERS - MY APPOINTMENT CONTROLLER
    public function userView(User $user, Appointment $appointment): Response
    {
        return $appointment->user_id === $user->id
            ? Response::allow()
            : Response::deny("You can't view other users' appointments.");
    }

    public function userEdit(User $user, Appointment $appointment): Response
    {
        if ($appointment->user_id != $user->id) {
            return Response::deny("You can't edit other users' appointments.");
        } elseif ($appointment->app_start_time <= now()) {
            return Response::deny("You can't edit appointments from the past.");
        } elseif ($appointment->deleted_at) {
            return Response::deny("You can't edit cancelled appointments.");
        } else {
            return Response::allow();
        }
    }

    public function userDelete(User $user, Appointment $appointment): Response
    {
        if ($appointment->user_id != $user->id) {
            return Response::deny("You can't cancel other users' appointments.");
        } elseif ($appointment->app_start_time <= now()) {
            return Response::deny("You can't cancel appointments from the past.");
        } elseif ($appointment->deleted_at) {
            return Response::deny("You can't cancel already cancelled appointments.");
        } else {
            return Response::allow();
        }
    }

    // FOR ADMINS - ADMIN APPOINTMENT CONTROLLER
    public function adminUpdate(User $user, Appointment $appointment): Response
    {
        if ($appointment->app_start_time <= now()) {
            return Response::deny("You can't edit " . $appointment->getType() . "s from the past.");
        } elseif ($appointment->deleted_at) {
            return Response::deny("You can't edit cancelled " . $appointment->getType() . "s.");
        } else {
            return Response::allow();
        }
    }

    public function adminDelete(User $user, Appointment $appointment): Response
    {
        if ($appointment->app_start_time <= now()) {
            return Response::deny("You can't cancel " . $appointment->getType() . "s from the past.");
        } elseif ($appointment->deleted_at) {
            return Response::deny("You can't cancel already cancelled " . $appointment->getType() . "s.");
        } else {
            return Response::allow();
        }
    }

    // UTILITY
    public function isTimeOff(User $user, Appointment $appointment): bool
    {
        return $appointment->service_id == 1;
    }
}
