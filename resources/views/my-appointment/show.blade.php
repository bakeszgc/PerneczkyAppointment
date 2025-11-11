<x-user-layout title="{{ __('appointments.appointment') . ' #' . $appointment->id }}">

    <div class="flex justify-between items-end align-bottom mb-4">
        <div>
            <x-breadcrumbs :links="[
                __('home.my_appointments') => $appointment->app_start_time <= now() ? route('my-appointments.index.previous') : route('my-appointments.index'),
                __('appointments.appointment') . ' #' . $appointment->id  => ''
            ]"/>
            <x-headline>
                {{ $appointment->app_start_time <= now() ? __('appointments.my_previous_appointment') : __('appointments.my_upcoming_appointment')}}
            </x-headline>
        </div>
        <div>
            <x-link-button :link="route('my-appointments.create')" role="createMain">
                <span class="max-sm:hidden">
                    {{ __('appointments.new_appointment') }}
                </span>
            </x-link-button>
        </div>
    </div>

    <x-appointment-card :appointment="$appointment" access="user" class="mb-4">
        <div class="text-base max-md:text-sm text-slate-500 mt-1">
            {{ __('appointments.comment') }}:
            @if (!$appointment->comment)
                <span class="italic">
                    {{ __('appointments.no_comment') }}
                </span>
            @else
                {{ $appointment->comment }}
            @endif
        </div>
    </x-appointment-card>

    <div class="text-center mb-4">
        <p>
            {{ $appointment->app_start_time >= now('Europe/Budapest') ? __('appointments.fresh_cut_earlier') : __('appointments.fresh_cut') }}
        </p>
        <a href="{{ route('my-appointments.create') }}" class="text-blue-700 hover:underline">
            {{ __('appointments.book_appointment_here') }}
        </a>
    </div>
</x-user-layout>