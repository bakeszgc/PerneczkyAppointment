<x-user-layout title="{{$type}} Bookings - " currentView="{{ $barber ? 'admin' : 'barber'}}">

    <x-breadcrumbs :links="$barber ? [
            'Admin Dashboard' => route('admin'),
            'Manage Barbers' => route('barbers.index'),
            $barber->getName() => route('barbers.show',$barber),
            'Bookings' => route('bookings.index',$barber),
            $type => ''
        ] : [
        'Bookings' => route('appointments.index'),
        $type => ''
    ]"/>


    <div class="flex justify-between items-end mb-4">
        <x-headline>
            @if ($barber)
                {{ $barber->getName() }}'s
            @endif

            @if ($type != null)
                {{$type}}
            @endif

            Bookings
        </x-headline>
        
        <x-link-button :link="$barber ? route('bookings.create',$barber) : route('appointments.create')" role="createMain">New&nbsp;Booking</x-link-button>
    </div>

    <div class="grid grid-cols-4 max-sm:grid-cols-2 gap-2 mb-4 p-2 rounded-md bg-slate-300 text-center text-lg font-bold">
        <a href="{{ $barber ? route('bookings.index',$barber) : route('appointments.index') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'All'])>All</a>

        <a href="{{ $barber ? route('bookings.upcoming',$barber) : route('appointments.upcoming') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Upcoming'])>Upcoming</a>

        <a href="{{ $barber ? route('bookings.previous',$barber) : route('appointments.previous') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Previous'])>Previous</a>

        <a href="{{ $barber ? route('bookings.cancelled',$barber) : route('appointments.cancelled') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Cancelled'])>Cancelled</a>
    </div>

    @if ($type === 'All')
        <x-calendar :calAppointments="$calAppointments" class="mb-4"></x-calendar>
    @endif
    
    @forelse ($appointments as $appointment)
        <x-appointment-card :appointment="$appointment" :showDetails="true" access="{{ $barber ? 'admin' : 'barber' }}" class="mb-4"/>
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