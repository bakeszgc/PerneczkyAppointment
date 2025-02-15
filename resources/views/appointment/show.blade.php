<x-layout title="{{$appointment->user->first_name}}'s Appointment - Perneczky BarberShop">
    <x-breadcrumbs :links="[
        'Appointments' => route('appointments.index'),
        $appointment->user->first_name . '\'s Appointment' => ''
    ]"/>

    <div class="flex justify-between items-bottom mb-4">
        <h1 class="font-bold text-4xl">{{$appointment->user->first_name}}'s Appointment</h1>
        <x-link-button :link="route('appointments.create')" role="createMain">Add New</x-link-button>
    </div>

    <x-appointment-card :appointment="$appointment" :editable="true" class="mb-8">
        <div class="text-base text-slate-500">
            Comment: {{$appointment->comment}}
        </div>
    </x-appointment-card>

    <h2 class="font-bold text-2xl mb-4">{{$appointment->user->first_name}}'s Other Appointments</h2>
    @foreach ($appointment->user->appointments as $otherAppointment)
        <x-appointment-card :appointment="$otherAppointment" class="mb-4"/>
    @endforeach
</x-layout>