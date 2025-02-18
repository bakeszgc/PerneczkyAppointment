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
    <x-card>
        <div class="text-center">
            <!-- <h2 class="font-bold text-2xl mb-4">Select Your Barber</h2> -->
            <div class="flex items-center gap-4 justify-center">
                @foreach ($barbers as $barber)
                    @if ((auth()->user()->barber->id ?? null) !== $barber->id)
                        @if ($service)
                            <x-link-button :link="route('my-appointments.create.date',['barber_id' => $barber->id,'service_id' => $service->id])">
                            <div>
                                    <img src="{{ asset('pfp/blank.png') }}" alt="BarberPic" class="mb-2 rounded-md h-30">
                                    <p>{{$barber->display_name ?? $barber->user->first_name}}</p>
                                </div>
                            </x-link-button>
                        @else
                            <x-link-button :link="route('my-appointments.create.service',['barber_id' => $barber->id])">
                            <div>
                                    <img src="{{ asset('pfp/blank.png') }}" alt="BarberPic" class="mb-2 rounded-md h-30">
                                    <p>{{$barber->display_name ?? $barber->user->first_name}}</p>
                                </div>
                            </x-link-button>
                        @endif
                    @endif
                @endforeach
            </div>
            
        </div>
    </x-card>
</x-user-layout>