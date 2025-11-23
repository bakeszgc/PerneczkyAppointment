<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ServicePolicy
{
    public function view(User $user, Service $service): Response
    {
        return $service->id === 1
            ? Response::deny(__('admin.error_service_view_not_auth'))
            : Response::allow();
    }

    public function update(User $user, Service $service): Response
    {
        if ($service->id ===1) {
            return Response::deny(__('admin.error_service_edit_not_auth'));
        } elseif ($service->deleted_at) {
            return Response::deny(__('admin.error_service_edit_deleted'));
        } else {
            return Response::allow();
        }
    }
    
    public function delete(User $user, Service $service): Response
    {
        if ($service->id ===1) {
            return Response::deny(__('admin.error_service_destroy_not_auth'));
        } elseif ($service->deleted_at) {
            return Response::deny(__('admin.error_service_destroy_deleted'));
        } else {
            return Response::allow();
        }
    }

    public function restore(User $user, Service $service): Response
    {
        if ($service->id ===1) {
            return Response::deny(__('admin.error_service_restore_not_auth'));
        } elseif (!isset($service->deleted_at)) {
            return Response::deny(__('admin.error_service_restore_active'));
        } else {
            return Response::allow();
        }
    }
}
