<x-user-layout title="{{$type}} Bookings - " currentView="{{ isset($barber) ? 'admin' : 'barber'}}">

    <x-breadcrumbs :links="isset($barber) ? [
            'Admin Dashboard' => route('admin'),
            'Manage Barbers' => route('barbers.index'),
            $barber->getName() => route('barbers.show',$barber),
            'Bookings' => route('bookings.barber',$barber),
            $type => ''
        ] : [
        'Bookings' => route('appointments.index'),
        $type => ''
    ]"/>


    <div class="flex justify-between items-end mb-4">
        <x-headline>
            @if (isset($barber))
                {{ $barber->getName() }}'s
            @endif

            @if ($type != null)
                {{$type}}
            @endif

            Bookings
        </x-headline>
        
        <x-link-button :link="isset($barber) ? route('bookings.create',$barber) : route('appointments.create')" role="createMain">New&nbsp;Booking</x-link-button>
    </div>

    <div class="grid grid-cols-4 max-sm:grid-cols-2 gap-2 mb-4 p-2 rounded-md bg-slate-300 text-center text-lg font-bold">
        <a href="{{ isset($barber) ? route('bookings.barber',$barber) : route('appointments.index') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'All'])>All</a>

        <a href="{{ isset($barber) ? route('bookings.barber.upcoming',$barber) : route('appointments.upcoming') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Upcoming'])>Upcoming</a>

        <a href="{{ isset($barber) ? route('bookings.barber.previous',$barber) : route('appointments.previous') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Previous'])>Previous</a>

        <a href="{{ isset($barber) ? route('bookings.barber.cancelled',$barber) : route('appointments.cancelled') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Cancelled'])>Cancelled</a>
    </div>

    @if ($type === 'All')
        @isset($barber)
            <x-calendar :calAppointments="$calAppointments" class="mb-4" :barber="$barber" />
        @else
            <x-calendar :calAppointments="$calAppointments" class="mb-4" />
        @endisset
    @endif
    
    @forelse ($appointments as $appointment)
        @isset($barber)
            <x-appointment-card :appointment="$appointment" :showDetails="true" access="admin" :barber="$barber" class="mb-4" />
        @else
            <x-appointment-card :appointment="$appointment" :showDetails="true" access="barber" class="mb-4" />
        @endisset
    @empty
        <x-empty-card>
            <p class="text-lg font-medium">You don't have any {{ lcfirst($type) }} appointments!</p>
            <a href="{{ isset($barber) ? route('bookings.create',$barber) : route('appointments.create') }}" class=" text-blue-700 hover:underline">Add a new booking here for one of your clients!</a>
        </x-empty-card>
    @endforelse

    <div class="mb-4">
        {{$appointments->links()}}
    </div>
    
</x-user-layout>