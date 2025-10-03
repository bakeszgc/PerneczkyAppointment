@php
    $access ??= 'barber';

    switch ($access) {
        case 'barber':
            $createRoute = route('time-offs.create');
            $title = "Your time off";
            $breadcrumbLinks = [
                'Time offs' => route('time-offs.index'),
                'Time off #' . $appointment->id => ''
            ];
        break;

        case 'admin':
            $createRoute = route('admin-time-offs.create');
            $title = $appointment->barber->getName() . '\'s Time Off';
            $breadcrumbLinks = [
                'Admin dashboard' => route('admin'),
                'Time offs' => route('admin-time-offs.index'),
                'Time off #' . $appointment->id => ''
            ];
        break;
    }
@endphp

<x-user-layout :title="$title" currentView="{{ $access }}">

    <div class="flex justify-between items-end align-bottom mb-4">
        <div>
            <x-breadcrumbs :links="$breadcrumbLinks"/>
            <x-headline>{{$title}}</x-headline>
        </div>

        <div>
            <x-link-button :link="$createRoute" role="timeoffCreateMain">
                <span class="max-sm:hidden">New&nbsp;time&nbsp;off</span>
            </x-link-button>
        </div>
    </div>

    <x-time-off-card :appointment="$appointment" :access="$access" class="mb-4" />

    <div class="text-center">
        Need a break{{ $appointment->app_start_time >= now('Europe/Budapest') ? ' earlier' : ''}}? <a href="{{ $createRoute }}" class="text-blue-700 hover:underline">Set a time off here!</a>
    </div>

</x-user-layout>