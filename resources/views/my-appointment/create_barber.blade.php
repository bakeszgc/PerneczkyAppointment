<x-user-layout title="Book an Appointment - Perneczky BarberShop">

    @if ($service)
        <x-breadcrumbs :links="[
            'Book an Appointment' => route('my-appointments.create'),
            'Select a Service' => route('my-appointments.create.service'),
            'Select a Barber' => ''
        ]"/>
    @else
        <x-breadcrumbs :links="[
            'Book an Appointment' => route('my-appointments.create'),
            'Select a Barber' => ''
        ]"/>
    @endif
    
    <h1 class="font-extrabold text-4xl mb-4">Select your Barber</h1>

    <div class="grid grid-cols-2 gap-4">
        @forelse ($barbers as $barber)
            <x-barber-card :barber="$barber" :service="$service">

            </x-barber-card>
        @empty
            
        @endforelse
    </div>
</x-user-layout>