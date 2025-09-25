@php
    $view = $view ?? 'user';

    switch($view) {
        case 'user':
            $breadcrumbLinks = [
                'Book an Appointment' => route('my-appointments.create'),
                'Select a Barber and a Service' => ''
            ];
            $createDateLink = route('my-appointments.create.date');
            break;
        case 'barber':
            $breadcrumbLinks = [
                'Bookings' => route('appointments.index'),
                'New Booking' => route('appointments.create'),
                'Select a Service' => ''    
            ];
            $createDateLink = route('appointments.create.date');
            break;
        case 'admin':
            $breadcrumbLinks = [
                'Admin Dashboard' => route('admin'),
                'Bookings' => route('bookings.index'),
                'Select a Barber and a Service' => ''
            ];
            $createDateLink = route('bookings.create.date');
            break;
    }
@endphp

<x-user-layout title="{{ $view == 'user' ? 'New Appointment' : 'New Booking'}}" currentView="{{ $view }}">

    <x-breadcrumbs :links="$breadcrumbLinks" />

    <form action="{{ $createDateLink }}" method="GET">

        @if ($view != 'barber' && isset($barbers))
            <x-headline class="mb-4">Select your Barber</x-headline>

            <div class="grid grid-cols-3 gap-4 mb-8">
                @forelse ($barbers as $barber)

                    <label for="barber_{{ $barber->id }}" class="border-2 border-[#0018d5] rounded-md p-4 cursor-pointer hover:bg-[#0018d5] hover:text-white has-[input:checked]:bg-[#0018d5] has-[input:checked]:shadow-2xl transition-all">
                        
                        <x-barber-picture :barber="$barber" />

                        <input type="radio" id="barber_{{ $barber->id }}" name="barber_id" value="{{ $barber->id }}" @checked(request('barber_id') && request('barber_id') == $barber->id) class="hidden">
                    </label>
                    
                @empty
                    <x-empty-card>
                        Sorry, there aren't any barbers available!
                    </x-empty-card>
                @endforelse
            </div>
        @endif

        <x-headline class="mb-4">Select your Service</x-headline>

        <div class="grid grid-cols-2 gap-4 mb-8">
            @forelse ($services as $service)
                <label for="service_{{ $service->id }}" class="border-2 border-[#0018d5] rounded-md p-4 cursor-pointer hover:bg-[#0018d5] hover:text-white has-[input:checked]:bg-[#0018d5] has-[input:checked]:text-white transition-all group has-[input:checked]:shadow-2xl">
                    <div class="flex justify-between items-start">
                        <h2 class="font-black text-lg">
                            {{ $service->name }}
                        </h2>
                        <p class="text-lg min-w-24 w-fit text-right">
                            {{number_format($service->price,thousands_separator:' ')}}&nbsp;HUF
                        </p>
                    </div>

                    <p class=" text-base text-slate-500 group-hover:text-white group-has-[input:checked]:text-white transition-all">Estimated duration: {{ $service->duration }} minutes</p>
                
                    <input type="radio" id="service_{{ $service->id }}" name="service_id" value="{{ $service->id }}" @checked(request('service_id') && request('service_id') == $service->id) class="hidden">
                </label>
            @empty
                <x-empty-card>
                    Sorry, there aren't any services available!
                </x-empty-card>
            @endforelse
        </div>

        <div class="mb-8">
            @if ($view != 'user')
                <input type="hidden" name="user_id" value="{{ request('user_id') }}">
            @endif
            
            <x-button role="ctaMain" :full="true" id="submitBtnForRadioBtns" :disabled="true">Check Available Dates</x-button>
        </div>

    </form>

    <script>
        // REMOVES DISABLED FROM SUBMIT BUTTON AFTER THE RADIO BUTTONS ARE CHECKED
        document.addEventListener('DOMContentLoaded', () => {
            
            const barberRadioButtons = document.querySelectorAll('input[name="barber_id"]');
            const serviceRadioButtons = document.querySelectorAll('input[name="service_id"]');
            const submitButton = document.getElementById('submitBtnForRadioBtns');

            if (submitButton) submitButton.disabled = true;

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
    </script>
</x-user-layout>