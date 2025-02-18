<x-user-layout title="{{$type}} Appointments - ">

    <x-breadcrumbs :links="[
        'Appointments' => route('appointments.index'),
        $type => ''
    ]"/>

    <div class="flex justify-between items-bottom mb-4">
        <h1 class="font-bold text-4xl">
            @if ($type != null)
                {{$type}}
            @endif
            Appointments
        </h1>
        <x-link-button :link="route('appointments.create')" role="createMain">Add New</x-link-button>
    </div>
    
    @foreach ($appointments as $appointment)
        <x-appointment-card :appointment="$appointment" access="barber" class="mb-4"/>
    @endforeach
    <div class="mb-4">
        {{$appointments->links()}}
    </div>
    
</x-user-layout>