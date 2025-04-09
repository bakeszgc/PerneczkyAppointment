<x-user-layout title="New Booking - " currentView="barber">
    <x-breadcrumbs :links="[
        'Bookings' => route('appointments.index'),
        'New Booking' => route('appointments.create'),
        'Select a Service' => ''
    ]"/>
    <x-headline class="mb-4">
        Select a Service
    </x-headline>
    
    <x-card class="mb-4 ">
        <ul class="text-center grid grid-cols-2 gap-4">
            @foreach ($services as $service)
                <x-link-button :full="true" :link="route('appointments.create.date',['user_id' => request('user_id'), 'service_id' => $service->id])">
                    <div>
                        <p>{{$service->name}}</p>
                        <p>{{$service->duration}} minutes - {{number_format($service->price,thousands_separator:' ')}} Ft</p>
                    </div>
                </x-button>
            @endforeach
        </ul>
    </x-card>

</x-user-layout>