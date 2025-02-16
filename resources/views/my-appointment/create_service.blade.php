<x-user-layout title="Book an Appointment - Perneczky BarberShop">
    @if ($barber)
        <x-breadcrumbs :links="[
            'Book an Appointment' => route('my-appointments.create'),
            'Select a Barber' => route('my-appointments.create.barber'),
            'Select a Service' => ''
        ]"/>
    @else
        <x-breadcrumbs :links="[
            'Book an Appointment' => route('my-appointments.create'),
            'Select a Service' => ''
        ]"/>
    @endif
    <h1 class="font-bold text-4xl mb-4">Book an Appointment</h1>
    <x-card>
        <div class="text-center mb-4">
            <h2 class="font-bold text-2xl mb-8">Select Your Service</h2>
            <div class="grid grid-cols-2 max-lg:grid-cols-1 gap-4">
                @foreach ($services as $service)
                    @if ($barber)
                        <x-link-button :full="true" :link="route('my-appointments.create.date',['barber_id' => $barber->id, 'service_id' => $service->id])">
                            <div>
                                <p>{{$service->name}}</p>
                                <p>{{$service->duration}} minutes - {{number_format($service->price,thousands_separator:' ')}} Ft</p>
                            </div>                        
                        </x-button>
                    @else
                        <x-link-button :full="true" :link="route('my-appointments.create.barber',['service_id' => $service->id])">
                            <div>
                                <p>{{$service->name}}</p>
                                <p>{{$service->duration}} minutes - {{number_format($service->price,thousands_separator:' ')}} Ft</p>
                            </div>                        
                        </x-button>
                    @endif
                @endforeach
            </div>
            
        </div>
    </x-card>
</x-user-layout>