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
            ? Response::deny('You are not authorized to view that page.')
            : Response::allow();
    }

    public function update(User $user, Service $service): Response
    {
        if ($service->id ===1) {
            return Response::deny('You are not authorized to view that page.');
        } elseif ($service->deleted_at) {
            return Response::deny("You can't edit deleted services. If you wish to proceed please restore " . $service->name . " first!");
        } else {
            return Response::allow();
        }
    }
    
    public function delete(User $user, Service $service): Response
    {
        if ($service->id ===1) {
            return Response::deny("You can't delete that service.");
        } elseif ($service->deleted_at) {
            return Response::deny("You can't delete already deleted services");
        } else {
            return Response::allow();
        }
    }

    public function restore(User $user, Service $service): Response
    {
        if ($service->id ===1) {
            return Response::deny("You can't restore that service.");
        } elseif (!isset($service->deleted_at)) {
            return Response::deny("You can't restore services that are not deleted yet");
        } else {
            return Response::allow();
        }
    }
}
