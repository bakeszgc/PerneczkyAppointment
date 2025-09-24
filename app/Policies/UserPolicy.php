<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    // FOR USERS - USER CONTROLLER
    public function view(User $user, User $model): Response
    {
        return $user->id === $model->id
            ? Response::allow()
            : Response::deny("You are not authorized to access other users's profile.");
    }

    public function update(User $user, User $model): Response
    {
        if ($user->id !== $model->id) {
            return Response::deny("You are not authorized to update other users's profile.");
        } elseif ($model->deleted_at) {
            return Response::deny("You can't update your profile because your user has been deleted.");
        } else {
            return Response::allow();
        }
    }

    public function updatePassword(User $user, User $model) {
        if ($user->id !== $model->id) {
            return Response::deny("You are not authorized to change other users's password.");
        } elseif ($model->deleted_at) {
            return Response::deny("You can't change your password because your user has been deleted.");
        } else {
            return Response::allow();
        }
    }
}
