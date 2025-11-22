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
            return Response::deny(__('admin.error_barber_edit_destroyed_user_1') . $barber->user->first_name . __('admin.error_barber_edit_destroyed_user_2'));
        } elseif ($barber->deleted_at) {
            return Response::deny(__('admin.error_barber_edit_destroyed_1') . $barber->user->first_name . __('admin.error_barber_edit_destroyed_2'));
        }

        return Response::allow();
    }

    public function delete(User $user, Barber $barber): Response
    {
        if ($barber->deleted_at) {
            return Response::deny(__('admin.error_barber_destroy_destroyed'));
        }
        return Response::allow();
    }

    public function restore(User $user, Barber $barber): Response
    {
        if (!isset($barber->deleted_at)) {
            return Response::deny(__('admin.error_barber_restore_active'));
        }
        return Response::allow();
    }
}
