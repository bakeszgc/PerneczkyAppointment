<x-user-layout title="Book an Appointment - Perneczky BarberShop">

    <x-breadcrumbs :links="[
        'Book an Appointment' => route('my-appointments.create'),
        'Select a Barber and a Service' => ''
    ]"/>

    <x-headline class="mb-4">Select your Barber</x-headline>

    <form action="{{ route('my-appointments.create.date') }}" method="GET">

        <div class="grid grid-cols-3 gap-4 mb-8">
            @forelse ($barbers as $barber)

                <label for="barber_{{ $barber->id }}" class="border-2 border-[#0018d5] rounded-md p-4 cursor-pointer hover:bg-[#0018d5] hover:text-white has-checked:bg-[#0018d5] transition-all">
                    
                    <div class="relative rounded-md overflow-hidden shadow-2xl">
                        <img src="{{ $barber->getPicture() }}" alt="{{ $barber->getName() }}" class=" rounded-md z-0">

                        <div class="w-full h-1/2 absolute bottom-0 left-0 z-10" style="background: linear-gradient(0deg,rgba(0, 0, 0, 1) 0%, rgba(255, 255, 255, 0) 100%);">
                            
                        </div>

                        <h2 class="font-black text-xl absolute bottom-4 left-4 text-white z-20">
                            {{ $barber->getName() }}
                        </h2>
                    </div>

                    <input type="radio" id="barber_{{ $barber->id }}" name="barber_id" value="{{ $barber->id }}" hidden {{ $barber_id && $barber_id == $barber->id ? 'checked="checked"' : ''}}>
                </label>
                
            @empty
                
            @endforelse
        </div>

        <x-headline class="mb-4">Select your Service</x-headline>

        <div class="grid grid-cols-2 gap-4 mb-8">
            @forelse ($services as $service)
                <label for="service_{{ $service->id }}" class="border-2 border-[#0018d5] rounded-md p-4 cursor-pointer hover:bg-[#0018d5] hover:text-white has-checked:bg-[#0018d5] has-checked:text-white transition-all group">
                    <div class="flex justify-between items-start">
                        <h2 class="font-black text-lg">
                            {{ $service->name }}
                        </h2>
                        <p class="text-lg min-w-24 w-fit text-right">
                            {{number_format($service->price,thousands_separator:' ')}}&nbsp;Ft
                        </p>
                    </div>

                    <p class=" text-base text-slate-500 group-hover:text-white transition-all">Estimated duration: {{ $service->duration }} minutes</p>
                
                    <input type="radio" id="service_{{ $service->id }}" name="service_id" value="{{ $service->id }}" hidden {{ $service_id && $service_id == $service->id ? 'checked="checked"' : ''}}>
                </label>
            @empty
                
            @endforelse
        </div>

        <div class="mb-8">
            <x-button role="ctaMain" :full="true">Check Available Dates</x-button>
        </div>

    </form>

</x-user-layout>