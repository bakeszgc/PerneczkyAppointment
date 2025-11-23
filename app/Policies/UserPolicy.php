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
            : Response::deny(__('auth.error_user_view_other'));
    }

    public function update(User $user, User $model): Response
    {
        if ($user->id !== $model->id) {
            return Response::deny(__('auth.error_user_edit_other'));
        } elseif ($model->deleted_at) {
            return Response::deny(__('auth.error_user_edit_destroyed'));
        } else {
            return Response::allow();
        }
    }

    public function updatePassword(User $user, User $model) {
        if ($user->id !== $model->id) {
            return Response::deny(__('auth.error_user_pw_other'));
        } elseif ($model->deleted_at) {
            return Response::deny(__('auth.error_user_pw_destroyed'));
        } else {
            return Response::allow();
        }
    }

    // FOR ADMINS - CUSTOMER CONTROLLER
    public function adminUpdate(User $user, User $model): Response
    {
        if ($model->deleted_at) {
            return Response::deny(__('admin.error_user_edit_destroyed'));
        } elseif(!$model->isRegistered()) {
            return Response::deny(__('admin.error_user_edit_unreg'));
        } else {
            return Response::allow();
        }
    }

    public function adminDelete(User $user, User $model): Response
    {
        if ($model->deleted_at) {
            return Response::deny(__('admin.error_user_destroy_destroyed'));
        } elseif ($model->id == $user->id) {
            return Response::deny("You can't delete your own account!");
        } else {
            return Response::allow();
        }
    }

    public function adminRestore(User $user, User $model): Response
    {
        if (!isset($model->deleted_at)) {
            return Response::deny(__('admin.error_user_restore_active'));
        } else {
            return Response::allow();
        }
    }
}
