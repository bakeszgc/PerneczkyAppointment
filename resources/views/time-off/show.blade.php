@php
    $access ??= 'barber';

    switch ($access) {
        case 'barber':
            $createRoute = route('time-offs.create');
            $title = __('barber.your_timeoff');
            $breadcrumbLinks = [
                __('home.time_offs') => route('time-offs.index'),
                __('barber.time_off') . ' #' . $appointment->id => ''
            ];
        break;

        case 'admin':
            $createRoute = route('admin-time-offs.create',['barber' => $appointment->barber_id]);
            $title = $appointment->barber->getName() . __('barber.s1s_timeoff');
            $breadcrumbLinks = [
                __('home.admin_dashboard') => route('admin'),
                __('home.time_offs') => route('admin-time-offs.index'),
                __('barber.time_off') . ' #' . $appointment->id => ''
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
                <span class="max-sm:hidden">
                    {{ __('barber.new_time_off') }}
                </span>
            </x-link-button>
        </div>
    </div>

    <x-time-off-card :appointment="$appointment" :access="$access" class="mb-4" />

    <div class="text-center mb-4">
        {{ $appointment->app_start_time >= now('Europe/Budapest') ? __('barber.need_break_earlier') : __('barber.need_break')}}
        
        <a href="{{ $createRoute }}" class="text-green-700 hover:underline">
            {{ __('barber.set_timeoff') }}
        </a>
    </div>

</x-user-layout>