<x-user-layout title="Book an Appointment - Perneczky BarberShop">

    <x-breadcrumbs :links="[
        'Book an Appointment' => route('my-appointments.create'),
        'Select a Barber and a Service' => ''
    ]"/>

    <x-headline class="mb-4">Select your Barber</x-headline>

    <form action="{{ route('my-appointments.create.date') }}" method="GET">

        <div class="grid grid-cols-3 gap-4 mb-8">
            @forelse ($barbers as $barber)

                <label for="barber_{{ $barber->id }}" class="border-2 border-[#0018d5] rounded-md p-4 cursor-pointer hover:bg-[#0018d5] hover:text-white has-[input:checked]:bg-[#0018d5] has-[input:checked]:shadow-2xl transition-all">
                    
                    <x-barber-picture :barber="$barber" />

                    <input type="radio" id="barber_{{ $barber->id }}" name="barber_id" value="{{ $barber->id }}" {{ $barber_id && $barber_id == $barber->id ? 'checked="checked"' : ''}} class="hidden">
                </label>
                
            @empty
                
            @endforelse
        </div>

        <x-headline class="mb-4">Select your Service</x-headline>

        <div class="grid grid-cols-2 gap-4 mb-8">
            @forelse ($services as $service)
                <label for="service_{{ $service->id }}" class="border-2 border-[#0018d5] rounded-md p-4 cursor-pointer hover:bg-[#0018d5] hover:text-white has-[input:checked]:bg-[#0018d5] has-[input:checked]:text-white transition-all group has-[input:checked]:shadow-2xl">
                    <div class="flex justify-between items-start">
                        <h2 class="font-black text-lg">
                            {{ $service->name }}
                        </h2>
                        <p class="text-lg min-w-24 w-fit text-right">
                            {{number_format($service->price,thousands_separator:' ')}}&nbsp;Ft
                        </p>
                    </div>

                    <p class=" text-base text-slate-500 group-hover:text-white group-has-[input:checked]:text-white transition-all">Estimated duration: {{ $service->duration }} minutes</p>
                
                    <input type="radio" id="service_{{ $service->id }}" name="service_id" value="{{ $service->id }}" hidden {{ $service_id && $service_id == $service->id ? 'checked="checked"' : ''}} class="hidden">
                </label>
            @empty
                
            @endforelse
        </div>

        <div class="mb-8">
            <x-button role="ctaMain" :full="true" id="ctaButton" :disabled="true">Check Available Dates</x-button>
        </div>

    </form>

</x-user-layout>