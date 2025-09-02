<x-card {{ $attributes->merge(['class' => 'flex gap-4']) }}>
    <div>
        <img src="{{ $barber->user->pfp_path ? asset('storage/pfp/' .  $barber->user->pfp_path) : asset('pfp/pfp_blank.png') }}" alt="{{$barber->getName()}}" class="h-40 rounded-md">
    </div>
    <div>
        <h2 class="font-bold text-xl">
            {{$barber->getName()}}
        </h2>
        <p class="mb-4">
            haircut
        </p>
        @if ($service)
            <x-link-button role="ctaMain" :link="route('my-appointments.create.date',['barber_id' => $barber->id,'service_id' => $service->id])">
                Book Appointment
            </x-link-button>
        @else
            <x-link-button role="ctaMain" :link="route('my-appointments.create.barber.service',['barber_id' => $barber->id])">
                Book Appointment
            </x-link-button>
        @endif
    </div>
</x-card>