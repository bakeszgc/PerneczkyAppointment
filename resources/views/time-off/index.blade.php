<x-user-layout title="{{ $type }} Time Offs - " currentView="barber">

    <x-breadcrumbs :links="[
        'Time Offs' => route('time-offs.index'),
        $type => ''
    ]"/>

    <div class="flex justify-between items-end mb-4">
        <x-headline>
            {{ $type }} Time Offs
        </x-headline>
        
        <x-link-button :link="route('time-offs.create')" role="timeoffMain">New&nbsp;Time&nbsp;Off</x-link-button>
    </div>

    <div class="grid grid-cols-3 gap-2 mb-4 p-2 rounded-md bg-slate-300 text-center text-lg font-bold">
        <a href="{{ route('time-offs.index') }}" @class(['p-2 rounded-md hover:bg-white transition-all' => $type != 'All', 'p-2 rounded-md bg-white' => $type == 'All'])>All</a>
        <a href="{{ route('time-offs.upcoming') }}" @class(['p-2 rounded-md hover:bg-white transition-all' => $type != 'Upcoming', 'p-2 rounded-md bg-white' => $type == 'Upcoming'])>Upcoming</a>
        <a href="{{ route('time-offs.previous') }}" @class(['p-2 rounded-md hover:bg-white transition-all' => $type != 'Previous', 'p-2 rounded-md bg-white' => $type == 'Previous'])>Previous</a>
    </div>

    @if ($type === 'All')
        <x-calendar :calAppointments="$calAppointments" class="mb-4"></x-calendar>
    @endif

    @forelse ($timeoffs as $timeoff)
        <x-time-off-card :appointment="$timeoff" :showDetails="true" class="mb-4"/>
    @empty
        <div class="text-center w-full rounded-md p-8 border border-dashed border-slate-500">
            <p class="text-lg font-medium">You don't have any {{ $type != "All" ? lcfirst($type) : '' }} time offs!</p>
            <a href="{{ route('time-offs.create') }}" class=" text-green-700 hover:underline">Set yourself one here!</a>
        </div>
    @endforelse

    <div class="mb-4">
        {{$timeoffs->links()}}
    </div>

</x-user-layout>