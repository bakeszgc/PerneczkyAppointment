<x-user-layout title="Appointment #{{ $appointment->id }}">

    <div class="flex justify-between items-end align-bottom mb-4">
        <div>
            <x-breadcrumbs :links="[
                'My appointments' => $appointment->app_start_time <= now() ? route('my-appointments.index.previous') : route('my-appointments.index'),
                'Appointment #' . $appointment->id  => ''
            ]"/>
            <x-headline>
                My {{ $appointment->app_start_time <= now() ? 'previous' : 'upcoming'}} appointment
            </x-headline>
        </div>
        <div>
            <x-link-button :link="route('my-appointments.create')" role="createMain">
                <span class="max-sm:hidden">New&nbsp;appointment</span>
            </x-link-button>
        </div>
    </div>

    <x-appointment-card :appointment="$appointment" access="user" class="mb-4">
        <div class="text-base max-md:text-sm text-slate-500 mt-1">
            Comment:
            @if (!$appointment->comment)
                <span class="italic">No comments from {{ $appointment->user->first_name }}.</span>
            @else
                {{ $appointment->comment }}
            @endif
        </div>
    </x-appointment-card>

    <div class="text-center mb-4">
        <p>Need a fresh cut{{ $appointment->app_start_time >= now('Europe/Budapest') ? ' earlier' : ''}}?</p>
        <a href="{{ route('my-appointments.create') }}" class="text-blue-700 hover:underline">Book an appointment here!</a>
    </div>
</x-user-layout>