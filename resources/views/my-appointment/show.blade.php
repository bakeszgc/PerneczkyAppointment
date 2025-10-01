<x-user-layout title="My appointment">
    <x-breadcrumbs :links="[
        'My appointments' => $appointment->app_start_time <= now() ? route('my-appointments.index.previous') : route('my-appointments.index'),
        'Appointment #' . $appointment->id  => ''
    ]"/>

    <x-headline class="mb-4">
        My {{ $appointment->app_start_time <= now() ? 'previous' : 'upcoming'}} appointment
    </x-headline>

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

    <div class="text-center">
        Need a fresh cut{{ $appointment->app_start_time >= now('Europe/Budapest') ? ' earlier' : ''}}? <a href="{{ route('my-appointments.create') }}" class="text-blue-700 hover:underline">Book an appointment here!</a>
    </div>
</x-user-layout>