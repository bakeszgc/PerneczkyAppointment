<x-user-layout title="Your {{$type != 'All' ? $type . ' ' : ' ' }}bookings" currentView="barber">

    <div class="flex justify-between items-end align-bottom mb-4">
        <div>
            <x-breadcrumbs :links="[
                'Bookings' => route('appointments.index')
            ]"/>
            <x-headline>
                Your

                @isset($type)
                    {{$type != 'All' ? strtolower($type) : ''}}
                @endisset

                bookings
            </x-headline>
        </div>

        <div>
            <x-link-button :link="route('appointments.create')" role="createMain">
                <span class="max-sm:hidden">New&nbsp;booking</span>
            </x-link-button>
        </div>
    </div>

    <div class="grid grid-cols-4 max-sm:grid-cols-2 gap-2 mb-4 p-2 max-sm:p-1 rounded-md bg-slate-300 text-center text-lg max-md:text-base font-bold">
        <a href="{{ route('appointments.index') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'All'])>All</a>

        <a href="{{ route('appointments.upcoming') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Upcoming'])>Upcoming</a>

        <a href="{{ route('appointments.previous') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Previous'])>Previous</a>

        <a href="{{ route('appointments.cancelled') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Cancelled'])>Cancelled</a>
    </div>

    @if ($type === 'All')
        <x-card class="mb-4">        
            <x-appointment-calendar :calAppointments="$calAppointments" access="barber" :barber="auth()->user()->barber" :barbers="$barbers"/>
        </x-card>
    @endif
    
    @forelse ($appointments as $appointment)
        <x-appointment-card :appointment="$appointment" :showDetails="true" access="barber" class="mb-4" />
    @empty
        <x-empty-card>
            <p class="text-lg max-md:text-base font-medium">You don't have any {{ $type !== 'All' ? lcfirst($type) : '' }} bookings!</p>
            <a href="{{ route('appointments.create') }}" class=" text-blue-700 hover:underline">Add a new appointment here for one of your clients!</a>
        </x-empty-card>
    @endforelse

    <div class="mb-4">
        {{$appointments->links()}}
    </div>
    
</x-user-layout>