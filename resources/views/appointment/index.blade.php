<x-user-layout title="Your {{$type != 'All' ? $type . ' ' : ' ' }}Bookings" currentView="barber">

    <x-breadcrumbs :links="[
        'Bookings' => route('appointments.index')
    ]"/>


    <div class="flex justify-between items-end mb-4">
        <x-headline>
            Your

            @if (isset($type))
                {{$type != 'All' ? $type : ''}}
            @endif

            Bookings
        </x-headline>
        
        <x-link-button :link="route('appointments.create')" role="createMain">New&nbsp;booking</x-link-button>
    </div>

    <div class="grid grid-cols-4 max-sm:grid-cols-2 gap-2 mb-4 p-2 rounded-md bg-slate-300 text-center text-lg font-bold">
        <a href="{{ route('appointments.index') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'All'])>All</a>

        <a href="{{ route('appointments.upcoming') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Upcoming'])>Upcoming</a>

        <a href="{{ route('appointments.previous') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Previous'])>Previous</a>

        <a href="{{ route('appointments.cancelled') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Cancelled'])>Cancelled</a>
    </div>

    @if ($type === 'All')
        <x-card class="mb-4">        
            <x-appointment-calendar :calAppointments="$calAppointments" />
        </x-card>
    @endif
    
    @forelse ($appointments as $appointment)
        <x-appointment-card :appointment="$appointment" :showDetails="true" access="barber" class="mb-4" />
    @empty
        <x-empty-card>
            <p class="text-lg font-medium">You don't have any {{ lcfirst($type) }} appointments!</p>
            <a href="{{ route('appointments.create') }}" class=" text-blue-700 hover:underline">Add a new booking here for one of your clients!</a>
        </x-empty-card>
    @endforelse

    <div class="mb-4">
        {{$appointments->links()}}
    </div>
    
</x-user-layout>