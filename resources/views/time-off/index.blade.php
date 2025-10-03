<x-user-layout title="Your {{ $type !== 'All' ? $type : '' }} time offs" currentView="barber">

    <div class="flex justify-between items-end mb-4">
        <div>
            <x-breadcrumbs :links="[
                'Time offs' => route('time-offs.index')
            ]"/>
            <x-headline>
                Your {{ $type !== 'All' ? strtolower($type) : '' }} time offs
            </x-headline>
        </div>
        
        <div>
            <x-link-button :link="route('time-offs.create')" role="timeoffCreateMain">
                <span class="max-sm:hidden">New&nbsp;time&nbsp;off</span>
            </x-link-button>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-2 mb-4 p-2 max-sm:p-1 rounded-md bg-slate-300 text-center text-lg max-md:text-base font-bold">
        <a href="{{ route('time-offs.index') }}" @class(['p-2 rounded-md hover:bg-white transition-all' => $type != 'All', 'p-2 rounded-md bg-white' => $type == 'All'])>All</a>
        <a href="{{ route('time-offs.upcoming') }}" @class(['p-2 rounded-md hover:bg-white transition-all' => $type != 'Upcoming', 'p-2 rounded-md bg-white' => $type == 'Upcoming'])>Upcoming</a>
        <a href="{{ route('time-offs.previous') }}" @class(['p-2 rounded-md hover:bg-white transition-all' => $type != 'Previous', 'p-2 rounded-md bg-white' => $type == 'Previous'])>Previous</a>
    </div>

    @if ($type === 'All')
        <x-card class="mb-4">        
            <x-appointment-calendar :calAppointments="$calAppointments" :barber="auth()->user()->barber" />
        </x-card>
    @endif

    @forelse ($timeoffs as $timeoff)
        <x-time-off-card :appointment="$timeoff" :showDetails="true" class="mb-4"/>
    @empty
        <x-empty-card>
            <p class="text-lg font-medium">You don't have any {{ $type != "All" ? lcfirst($type) : '' }} time offs!</p>
            <a href="{{ route('time-offs.create') }}" class=" text-green-700 hover:underline">Set yourself one here!</a>
        </x-empty-card>
    @endforelse

    <div class="mb-4">
        {{$timeoffs->links()}}
    </div>

</x-user-layout>