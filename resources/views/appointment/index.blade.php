<x-user-layout title="{{$type}} Appointments - " currentView="barber">

    <x-breadcrumbs :links="[
        'Appointments' => route('appointments.index'),
        $type => ''
    ]"/>

    <div class="flex justify-between items-bottom mb-4">
        <h1 class="font-extrabold text-4xl">
            @if ($type != null)
                {{$type}}
            @endif
            Appointments
        </h1>
        <x-link-button :link="route('appointments.create')" role="createMain">Add New</x-link-button>
    </div>

    <div class="grid grid-cols-4 gap-2 mb-4 p-2 rounded-md bg-slate-300 text-center text-lg font-bold">
        <a href="{{ route('appointments.index') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'All'])>All</a>
        <a href="{{ route('appointments.upcoming') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Upcoming'])>Upcoming</a>
        <a href="{{ route('appointments.previous') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Previous'])>Previous</a>
        <a href="{{ route('appointments.cancelled') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Cancelled'])>Cancelled</a>
    </div>
    
    @forelse ($appointments as $appointment)
        <x-appointment-card :appointment="$appointment" access="barber" class="mb-4"/>
    @empty
        <div class="text-center w-full rounded-md p-8 border border-dashed border-slate-500">
            <p class="text-lg font-medium">You don't have any {{ lcfirst($type) }} appointments!</p>
            <a href="{{ route('appointments.create') }}" class=" text-blue-700 hover:underline">Add a new booking here for one of your clients!</a>
        </div>
    @endforelse

    <div class="mb-4">
        {{$appointments->links()}}
    </div>
    
</x-user-layout>