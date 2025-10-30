@php
    $view = $view ?? 'user';

    switch($view) {
        case 'user':
            $breadcrumbLinks = [
                'Barber & service' => ''
            ];
            $createDateLink = route('my-appointments.create.earliest');
            $steps = [true,false,false];
            break;
        case 'barber':
            $breadcrumbLinks = [
                'Bookings' => route('appointments.index'),
                'New booking' => route('appointments.create'),
                'Service' => ''    
            ];
            $createDateLink = route('appointments.create.date');
            break;
        case 'admin':
            $breadcrumbLinks = [
                'Admin dashboard' => route('admin'),
                'Bookings' => route('bookings.index'),
                'New booking' => route('bookings.create'),
                'Barber & service' => ''
            ];
            $createDateLink = route('bookings.create.date');
            break;
    }
@endphp

<x-user-layout title="New {{ $view == 'user' ? 'appointment' : 'booking'}}" currentView="{{ $view }}">

    <x-breadcrumbs :links="$breadcrumbLinks" />

    <form action="{{ $createDateLink }}" method="GET">

        @if ($view != 'barber' && isset($barbers))

            <div class="flex justify-between">
                <x-headline class="mb-4 blue-300">Select your barber</x-headline>
                
                @if ($view == 'user')
                    <div class="w-16 flex gap-1">                
                        @foreach ($steps as $step)
                            <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="50" cy="50" r="40" stroke="#93c5fd" stroke-width="6" fill="{{ $step ? '#93c5fd' : 'none' }}" />
                            </svg>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-3 max-md:grid-cols-2 gap-4 mb-16">
                @if ($barbers->count() >=1)
                    <label for="earliest" class="border-2 border-[#0018d5] rounded-md p-4 max-md:p-2 cursor-pointer hover:bg-[#0018d5] hover:text-white has-[input:checked]:bg-[#0018d5] has-[input:checked]:shadow-2xl transition-all">
                        
                        <x-barber-picture barber="earliest" />

                        <input type="radio" id="earliest" name="barber_id" value="earliest" @checked(request('barber_id') && request('barber_id') == 'earliest') class="hidden">
                    </label>
                @endif

                @forelse ($barbers as $barber)

                    <label for="barber_{{ $barber->id }}" class="border-2 border-[#0018d5] rounded-md p-4 max-md:p-2 cursor-pointer hover:bg-[#0018d5] hover:text-white has-[input:checked]:bg-[#0018d5] has-[input:checked]:shadow-2xl transition-all">
                        
                        <x-barber-picture :barber="$barber" />

                        <input type="radio" id="barber_{{ $barber->id }}" name="barber_id" value="{{ $barber->id }}" @checked(request('barber_id') && request('barber_id') == $barber->id) class="hidden">
                    </label>
                    
                @empty
                    <x-empty-card class="col-span-3 max-md:col-span-2">
                        Sorry, there aren't any barbers available yet. Please check back later!
                    </x-empty-card>
                @endforelse
            </div>

            <div id="service"></div>
        @endif
        
        <x-headline class="mb-4">Select your service</x-headline>

        <div class="grid grid-cols-2 max-md:grid-cols-1 gap-4 mb-8">
            @forelse ($services as $service)
                <label for="service_{{ $service->id }}" class="border-2 border-[#0018d5] rounded-md p-4 cursor-pointer hover:bg-[#0018d5] hover:text-white has-[input:checked]:bg-[#0018d5] has-[input:checked]:text-white transition-all group has-[input:checked]:shadow-2xl">
                    <div class="flex justify-between items-start">
                        <h2 class="font-black text-lg max-md:text-base">
                            {{ $service->name }}
                        </h2>
                        <p class="text-lg max-md:text-base min-w-24 w-fit text-right">
                            {{number_format($service->price,thousands_separator:' ')}}&nbsp;HUF
                        </p>
                    </div>

                    <p class="text-base max-md:text-sm text-slate-500 group-hover:text-white group-has-[input:checked]:text-white transition-all">Estimated duration: {{ $service->duration }} minutes</p>
                
                    <input type="radio" id="service_{{ $service->id }}" name="service_id" value="{{ $service->id }}" @checked(request('service_id') && request('service_id') == $service->id) class="hidden">
                </label>
            @empty
                <x-empty-card class="col-span-3 max-md:col-span-2">
                    Sorry, there aren't any services available yet. Please check back later!
                </x-empty-card>
            @endforelse
        </div>

        <div class="mb-8">
            @if ($view != 'user')
                <input type="hidden" name="user_id" value="{{ request('user_id') }}">
            @endif
            
            <x-button role="ctaMain" :full="true" id="submitBtnForRadioBtns" :disabled="true">Check available dates</x-button>
        </div>

    </form>

    <script>
        // REMOVES DISABLED FROM SUBMIT BUTTON AFTER THE RADIO BUTTONS ARE CHECKED
        document.addEventListener('DOMContentLoaded', () => {
            
            const barberRadioButtons = document.querySelectorAll('input[name="barber_id"]');
            const serviceRadioButtons = document.querySelectorAll('input[name="service_id"]');
            const serviceAnchor = document.getElementById('service');
            const submitButton = document.getElementById('submitBtnForRadioBtns');

            if (submitButton) submitButton.disabled = true;

            if (barberRadioButtons.length > 0) {
                barberRadioButtons.forEach(radio => {
                    radio.addEventListener('change', function () {
                        jumpTo(serviceAnchor);
                    });
                });
            }

            serviceRadioButtons.forEach(radio => {
                radio.addEventListener('change', function () {
                    jumpTo(submitButton);
                });
            });

            if (serviceRadioButtons.length > 0) {

                checkBarberServiceRadioButtons(barberRadioButtons, serviceRadioButtons, submitButton);

                serviceRadioButtons.forEach(serviceButton => {
                    serviceButton.addEventListener('change', function () {
                        checkBarberServiceRadioButtons(barberRadioButtons, serviceRadioButtons, submitButton);
                    });
                });
                
                if(barberRadioButtons.length > 0) {
                    barberRadioButtons.forEach(barberButton => {
                        barberButton.addEventListener('change', function () {
                            checkBarberServiceRadioButtons(barberRadioButtons, serviceRadioButtons, submitButton);
                        });
                    });
                }
            }
        });

        function checkBarberServiceRadioButtons(barberRadioButtons, serviceRadioButtons, submitButton) {

            var isAnyBarbersChecked = Array.from(barberRadioButtons).some(radio => radio.checked);

            if (barberRadioButtons.length == 0) {
                isAnyBarbersChecked = true;
            }
            
            const isAnyServicesChecked = Array.from(serviceRadioButtons).some(radio => radio.checked);

            if (submitButton) submitButton.disabled = !isAnyBarbersChecked || !isAnyServicesChecked;
        }

        function jumpTo (anchor) {
            setTimeout(() => {
                const top = anchor.offsetTop - 100;
                window.scrollTo(0,top);
            }, 300);
        }
    </script>
</x-user-layout>