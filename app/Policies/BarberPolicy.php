<?php

namespace App\Policies;

use App\Models\Barber;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BarberPolicy
{
    public function update(User $user, Barber $barber): Response
    {
        if ($barber->user->deleted_at) {
            return Response::deny("You can't edit the barber page of a deleted user. If you want to proceed be sure to restore " . $barber->user->first_name . "'s account first.");
        } elseif ($barber->deleted_at) {
            return Response::deny("You can't edit the barber page of a deleted barber. If you want to proceed be sure to restore " . $barber->user->first_name . "'s barber access first.");
        }

        return Response::allow();
    }

    public function delete(User $user, Barber $barber): Response
    {
        if ($barber->deleted_at) {
            return Response::deny("You can't delete an already deleted barber.");
        }
        return Response::allow();
    }

    public function restore(User $user, Barber $barber): Response
    {
        if (!isset($barber->deleted_at)) {
            return Response::deny("You can't restore an active barber.");
        }
        return Response::allow();
    }
}
