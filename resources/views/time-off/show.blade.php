@php
    $access ??= 'barber';

    switch ($access) {
        case 'barber':
            $createRoute = route('time-offs.create');
            $title = "Your Time Off";
            $breadcrumbLinks = [
                'Time Offs' => route('time-offs.index'),
                'Your Time Off' => ''
            ];
        break;

        case 'admin':
            $createRoute = route('admin-time-offs.create');
            $title = $appointment->barber->getName() . '\'s Time Off';
            $breadcrumbLinks = [
                'Admin Dashboard' => route('admin'),
                'Time Offs' => route('admin-time-offs.index'),
                $title => ''
            ];
        break;
    }
@endphp

<x-user-layout :title="$title" currentView="{{ $access }}">
    <x-breadcrumbs :links="$breadcrumbLinks"/>

    <div class="flex justify-between items-end mb-4">
        <x-headline>{{$title}}</x-headline>
        <x-link-button :link="$createRoute" role="timeoffCreateMain">New&nbsp;Time&nbsp;Off</x-link-button>
    </div>

    <x-time-off-card :appointment="$appointment" :access="$access" class="mb-4" />

    <div class="text-center">
        Need a break{{ $appointment->app_start_time >= now('Europe/Budapest') ? ' earlier' : ''}}? <a href="{{ route('time-offs.create') }}" class="text-blue-700 hover:underline">Set a time off here!</a>
    </div>

</x-user-layout>