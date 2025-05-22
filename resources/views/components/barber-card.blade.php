<x-card class="border border-blue-600 rounded-md flex gap-4 p-4">
    <div>
        <img src="{{ asset('pfp/blank.png') }}" alt="{{$barber->display_name ?? $barber->user->first_name}}" class="h-40">
    </div>
    <div>
        <h2 class="font-bold text-xl">
            {{$barber->display_name ?? $barber->user->first_name}}
        </h2>
        <p class="mb-4">
            haircut
        </p>
        @if ($service)
            <x-link-button role="loginMain" :link="route('my-appointments.create.date',['barber_id' => $barber->id,'service_id' => $service->id])">
                Book Appointment
            </x-link-button>
        @else
            <x-link-button role="loginMain" :link="route('my-appointments.create.service',['barber_id' => $barber->id])">
                Book Appointment
            </x-link-button>
        @endif
    </div>
</x-card>