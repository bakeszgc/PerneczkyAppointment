<x-user-layout title="My Time Off - " currentView="barber">
    <x-breadcrumbs :links="[
        'Time Offs' => route('time-offs.index'),
        'My Time Off' => ''
    ]"/>

    <div class="flex justify-between items-end mb-4">
        <x-headline>{{ $appointment->barber->display_name ?? $appointment->barber->user->first_name}}'s Time Off</x-headline>
        <x-link-button :link="route('time-offs.create')" role="timeoffMain">New&nbsp;Time&nbsp;Off</x-link-button>
    </div>

    <x-time-off-card :appointment="$appointment">

    </x-time-off-card>

</x-user-layout>