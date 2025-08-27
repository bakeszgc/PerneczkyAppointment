<x-user-layout title="Your Time Off" currentView="barber">
    <x-breadcrumbs :links="[
        'Time Offs' => route('time-offs.index'),
        'Your Time Off' => ''
    ]"/>

    <div class="flex justify-between items-end mb-4">
        <x-headline>Your Time Off</x-headline>
        <x-link-button :link="route('time-offs.create')" role="timeoffMain">New&nbsp;Time&nbsp;Off</x-link-button>
    </div>

    <x-time-off-card :appointment="$appointment" class="mb-4" />

    <div class="text-center">
        Need a break{{ $appointment->app_start_time >= now('Europe/Budapest') ? ' earlier' : ''}}? <a href="{{ route('time-offs.create') }}" class="text-blue-700 hover:underline">Set a time off here!</a>
    </div>

</x-user-layout>