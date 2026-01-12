@php
    $view ??= 'user';
    $action ??= 'create';

    $steps = [true,false,false];

    switch($view) {
        case 'user':
            switch($action) {
                case 'create':
                    $breadcrumbLinks = [
                        __('appointments.barber_and_service') => ''
                    ];
                    $createDateLink = route('my-appointments.create.earliest');
                break;

                case 'edit':
                    $breadcrumbLinks = [
                        __('home.my_appointments') => route('my-appointments.index'),
                        __('appointments.appointment') .' #' . $appointment->id => route('my-appointments.show',$appointment),
                        __('appointments.barber_and_service') => ''
                    ];
                    $createDateLink = route('my-appointments.edit.earliest',$appointment);
                break;
            }
            
            break;
        case 'barber':
            $breadcrumbLinks = [
                __('home.bookings') => route('appointments.index'),
                __('appointments.barber_and_service') => ''    
            ];
            $createDateLink = route('appointments.create.earliest');
            $steps[] = false;
            break;
        case 'admin':
            $breadcrumbLinks = [
                __('home.admin_dashboard') => route('admin'),
                __('home.bookings') => route('bookings.index'),
                __('appointments.barber_and_service') => ''
            ];
            $steps[] = false;
            $createDateLink = route('bookings.create.earliest');
            break;
    }
@endphp

<x-user-layout title="{{ $view == 'user' ? __('appointments.new_appointment') : __('appointments.new_booking')}}" currentView="{{ $view }}">

    <x-breadcrumbs :links="$breadcrumbLinks" />

    <form action="{{ $createDateLink }}" method="GET">

        @if ($view != 'user' && request('user_id') != null)
            <input type="hidden" name="user_id" value="{{ request('user_id') }}">
        @endif

        @if (isset($barbers))

            <div id="barber"></div>
            <div class="flex justify-between">
                <x-headline class="mb-4 blue-300">
                    {{ __('appointments.select_your_barber') }}
                </x-headline>
                
                <div class="w-16 flex gap-1">                
                    @foreach ($steps as $step)
                        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="40" stroke="#93c5fd" stroke-width="6" fill="{{ $step ? '#93c5fd' : 'none' }}" />
                        </svg>
                    @endforeach
                </div>
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

                        <input type="radio" id="barber_{{ $barber->id }}" name="barber_id" value="{{ $barber->id }}"
                            @checked(
                                (($view = 'barber' && !request('barber_id')) && auth()->user()?->barber->id == $barber->id) ||
                                (request('barber_id') && request('barber_id') == $barber->id) ||
                                (isset($appointment) && $appointment->barber_id == $barber->id))
                        class="hidden">
                    </label>
                    
                @empty
                    <x-empty-card class="col-span-3 max-md:col-span-2">
                        {{ __('home.barbers_empty') }}
                    </x-empty-card>
                @endforelse
            </div>

            
        @endif

        <div id="service"></div>
        <div class="flex justify-between">
            <x-headline class="mb-4 blue-300">
                {{ __('appointments.select_your_service') }}
            </x-headline>
        </div>

        <div class="grid grid-cols-2 max-md:grid-cols-1 gap-4 mb-8">
            @forelse ($services as $service)
                <label for="service_{{ $service->id }}" class="border-2 border-[#0018d5] rounded-md p-4 cursor-pointer hover:bg-[#0018d5] hover:text-white has-[input:checked]:bg-[#0018d5] has-[input:checked]:text-white transition-all group has-[input:checked]:shadow-2xl">
                    <div class="flex justify-between items-start">
                        <h2 class="font-black text-lg max-md:text-base">
                            {{ $service->getName() }}
                        </h2>
                        <p class="text-lg max-md:text-base min-w-24 w-fit text-right">
                            {{number_format($service->price,thousands_separator:' ')}}&nbsp;HUF
                        </p>
                    </div>

                    <p class="text-base max-md:text-sm text-slate-500 group-hover:text-white group-has-[input:checked]:text-white transition-all">{{ __('home.estimated_duration') . ': ' . $service->duration . ' ' . __('home.minutes') }} </p>
                
                    <input type="radio" id="service_{{ $service->id }}" name="service_id" value="{{ $service->id }}" @checked((request('service_id') && request('service_id') == $service->id) || (isset($appointment) && $appointment->service_id == $service->id)) class="hidden">
                </label>
            @empty
                <x-empty-card class="col-span-3 max-md:col-span-2">
                    {{ __('home.services_empty') }}
                </x-empty-card>
            @endforelse
        </div>

        <div class="mb-8">            
            <x-button role="ctaMain" :full="true" id="submitBtnForRadioBtns" :disabled="true">
                {{ __('appointments.check_available_dates') }}
            </x-button>
        </div>

    </form>

    <script>
        // REMOVES DISABLED FROM SUBMIT BUTTON AFTER THE RADIO BUTTONS ARE CHECKED
        document.addEventListener('DOMContentLoaded', () => {
            
            const barberRadioButtons = document.querySelectorAll('input[name="barber_id"]');
            const serviceRadioButtons = document.querySelectorAll('input[name="service_id"]');
            const serviceAnchor = document.getElementById('service');
            const barberAnchor = document.getElementById('barber');
            const submitButton = document.getElementById('submitBtnForRadioBtns');

            if (submitButton) submitButton.disabled = true;

            if (barberRadioButtons.length > 0) {
                barberRadioButtons.forEach(radio => {
                    radio.addEventListener('change', function () {
                        const isAnyServicesChecked = Array.from(serviceRadioButtons).some(radio => radio.checked);

                        if (isAnyServicesChecked) {
                            jumpTo(submitButton);
                        } else {
                            jumpTo(serviceAnchor);
                        }
                    });
                });
            }

            serviceRadioButtons.forEach(radio => {
                radio.addEventListener('change', function () {
                    jumpTo(submitButton);

                    const isAnyBarbersChecked = Array.from(barberRadioButtons).some(radio => radio.checked);

                    if (isAnyBarbersChecked) {
                        jumpTo(submitButton);
                    } else {
                        jumpTo(barberAnchor);
                    }
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
    </script>
</x-user-layout>