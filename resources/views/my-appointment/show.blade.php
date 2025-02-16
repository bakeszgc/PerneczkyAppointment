<x-user-layout>
    <x-breadcrumbs :links="[
        'My Appointments' => route('my-appointments.index'),
        'Appointment #' . $appointment->id  => ''
    ]"/>

    <h1 class="font-bold text-4xl mb-4">
        My {{ $appointment->app_start_time <= now() ? 'previous' : 'upcoming'}} appointment
    </h1>

    <x-appointment-card :appointment="$appointment" access="user" class="mb-4">
        <div class="text-base text-slate-500">
            Comment:
            @if (!$appointment->comment)
                <span class="italic">No comments from {{ $appointment->user->first_name }}</span>
            @else
                {{ $appointment->comment }}
            @endif
        </div>
    </x-appointment-card>

    <div class="text-center">
        Need a fresh cut{{ $appointment->app_start_time >= now('Europe/Budapest') ? ' earlier' : ''}}? <a href="{{ route('my-appointments.create') }}" class="text-blue-700 hover:underline">Book an appointment here!</a>
    </div>
</x-user-layout>