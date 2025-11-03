@php
    use Carbon\Carbon;

    $view ??= 'user';
    $action ??= 'create';
    $steps = [true, true, false];

    switch($view) {
        case 'user':
            switch($action) {
                case 'create':
                    $serviceLink = route('my-appointments.create.barber.service',['service_id' => $service->id, 'barber_id' => $barber->id]);

                    $storeLink = route('my-appointments.create.confirm',['barber_id' => $barber->id, 'service_id' => $service->id]);

                    $breadcrumbLinks = [
                        'Barber & service' => $serviceLink,
                        'Date & time' => ''
                    ];
                break;

                case 'edit':
                    $serviceLink = route('my-appointments.edit.barber.service',['my_appointment' => $appointment, 'service_id' => $service->id, 'barber_id' => $barber->id]);

                    $breadcrumbLinks = [
                        'My appointments' => route('my-appointments.index'),
                        'Appointment #' . $appointment->id => route('my-appointments.show',$appointment),
                        'Barber & service' => $serviceLink,
                        'Date & time' => ''
                    ];

                    $storeLink = route('my-appointments.edit.confirm',['barber_id' => $barber->id, 'service_id' => $service->id, 'my_appointment' => $appointment]);
                break;
            }
            break;

        case 'barber':
            $serviceLink = route('appointments.create.service',['service_id' => $service->id]);
            $storeLink = route('appointments.create.customer',['service_id' => $service->id]);

            $breadcrumbLinks = [
                'Bookings' => route('appointments.index'),
                'Service' => $serviceLink,
                'Date & time' => ''
            ];

            $steps[] = false;
            
            break;

        case 'admin':
            $serviceLink = route('bookings.create.barber.service',['barber_id' => $barber->id, 'service_id' => $service->id]);
            $storeLink = route('bookings.create.customer',['service_id' => $service->id, 'barber_id' => $barber->id]);

            $breadcrumbLinks = [
                'Admin dashboard' => route('admin'),
                'Bookings' => route('bookings.index'),
                'Barber & service' => $serviceLink,
                'Date & time' => ''
            ];

            $steps[] = false;

            break;
    }
@endphp

<x-user-layout title="New {{ $view == 'user' ? 'appointment' : 'booking' }}" currentView="{{ $view }}">
    <x-breadcrumbs :links="$breadcrumbLinks"/>

    <div class="flex justify-between">
        <x-headline class="mb-4 blue-300">Select your date</x-headline>
        <div class="w-16 flex gap-1">                
            @foreach ($steps as $step)
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="40" stroke="#93c5fd" stroke-width="6" fill="{{ $step ? '#93c5fd' : 'none' }}" />
                </svg>
            @endforeach
        </div>
    </div>

    <form action="{{$storeLink}}" method="GET">        

        @if ($view != 'barber')
            <input type="hidden" name="barber_id" value="{{ $barber->id }}">
        @endif
        
        <input type="hidden" name="service_id" value="{{ $service->id }}">

        <x-card class="mb-4">
            <div class="flex justify-between gap-4">
                <div class="flex flex-1 flex-col justify-between">
                    <div class="mb-4">
                        <h2 class="font-bold text-2xl max-md:text-lg mb-2">{{ $view == 'user' ? 'Your' : 'New'}} appointment</h2>

                        <h3 class="font-medium text-lg max-md:text-base">
                            <a href="{{ $serviceLink }}">
                                {{$service->name}}
                                
                                <span class="max-sm:hidden">•</span>
                                <br class="sm:hidden">
                                
                                {{$service->duration}} minutes
                                •
                                {{number_format($service->price,thousands_separator:' ')}} HUF
                            </a>
                        </h3>
                        <p class="font-medium text-base text-slate-500">
                            <a href="{{ $serviceLink }}">
                            Barber: {{$barber->getName()}}
                            </a>
                        </p>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label for="comment">Wanna leave some comments for this appointment? Share with us below!</label>
                        <x-input-field type="textarea" name="comment" id="comment" class="w-full">{{ old('comment') ?? request('comment') ?? (isset($appointment) ? $appointment->comment : '')}}</x-input-field>
                    </div>
                </div>
                <div class="flex-shrink-0 max-md:hidden rounded-md h-52 w-auto overflow-hidden">
                    <img src="{{ $barber->getPicture() }}" alt="{{ $barber->getName() }}" class="h-52 w-auto transition-all hover:scale-105">
                </div>
            </div>
        </x-card>

        <x-card class="mb-4">
            <div x-data="appointmentCalendar()" x-init="init()" class="grid grid-cols-2 max-md:grid-cols-1 gap-8">
                @php
                    //$startOfCurrentMonth = (new Carbon('2025-06-20'))->startOfMonth();
                    $startOfCurrentMonth = now()->startOfMonth();
                @endphp

                <div class="overflow-hidden">
                    <div class="w-[200%] flex gap-4 transition-all mb-4" id="calendarContainter">
                        <x-booking-calendar :firstDaytOfMonth="$startOfCurrentMonth" :availableSlotsByDate="$availableSlotsByDate" />

                        <x-booking-calendar :firstDaytOfMonth="$startOfCurrentMonth->addMonth()" :availableSlotsByDate="$availableSlotsByDate" />
                    </div>

                    <div class="flex justify-between px-2 *:transition-all *:rounded-full *:p-2">
                        <button type="button" id="previousMonthButton" disabled class="disabled:text-slate-300 hover:bg-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </button>

                        <button type="button" id="nextMonthButton" class="disabled:text-slate-300 hover:bg-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <template x-if="selectedDate">
                        <div>
                            <h2 class="w-full text-center mb-4 text-xl max-md:text-lg font-bold">
                                Free timeslots on <span x-text="selectedDate"></span>
                            </h2>
                            
                            <template x-if="slotsByDate[selectedDate]?.length">
                                <div class="grid grid-cols-6 max-md:grid-cols-5 max-sm:grid-cols-4 gap-2 text-center">
                                    <template x-for="(time, index) in slotsByDate[selectedDate]" :key="`${selectedDate}_${time}`">
                                        <label :for="`date_${selectedDate}_${time}`" class="font-semibold border-2 border-[#0018d5] text-[#0018d5] rounded-md p-2 cursor-pointer hover:bg-[#0018d5] hover:text-white has-[input:checked]:bg-[#0018d5] has[input:checked]:shadow-2xl has-[input:checked]:text-white transition-all">

                                            <span x-text="time"></span>

                                            <input type="radio" name="date" class="hidden" :id="`date_${selectedDate}_${time}`" :value="`${selectedDate} ${time}`" x-model="selectedTime">
                                        </label>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </x-card>

        <div class="mb-8">
            <x-button role="ctaMain" :full="true" id="submitBtnForRadioBtns" :disabled="true">Book appointment</x-button>
        </div>
    </form>

    <script>

        function appointmentCalendar() {
            return {
                selectedTime: null,
                selectedDate: null,
                slotsByDate: @json($availableSlotsByDate),

                init() {
                    this.selectedDate = "{{ substr(request('date'),0,10) }}" || "{{ isset($appointment) ? substr($appointment->app_start_time,0,10) : null }}" || Object.keys(this.slotsByDate)[0] || null;                    
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {

            // NEXT MONTH + PREVIOUS MONTH BUTTONS
            const calendarContainter = document.getElementById('calendarContainter');
            const nextMonthButton = document.getElementById('nextMonthButton');
            const previousMonthButton = document.getElementById('previousMonthButton');            

            nextMonthButton.addEventListener('click', () => {
                checkNextMonth(calendarContainter, nextMonthButton, previousMonthButton);
            });

            previousMonthButton.addEventListener('click', () => {
                calendarContainter.classList.remove('-translate-x-1/2');
                previousMonthButton.setAttribute('disabled','');
                nextMonthButton.removeAttribute('disabled','');
            });

            // REMOVING DISABLED FROM SUBMIT BUTTON AFTER A TIMESLOT GETS SELECTED 
            const dayRadioButtons = document.querySelectorAll('input[name="day"]');
            const submitButton = document.getElementById('submitBtnForRadioBtns');

            if (submitButton) submitButton.disabled = true;

            checkDateRadioButtons(submitButton);
            if (dayRadioButtons) {
                dayRadioButtons.forEach(dayButton => {
                    if (dayButton.value == "{{ request('date') ? substr(request('date'),0,10) : (isset($appointment) ? substr($appointment->app_start_time,0,10) : false) }}") {
                        dayButton.checked = true;

                        if (new Date(dayButton.value).getMonth() > new Date().getMonth()) {
                            checkNextMonth(calendarContainter, nextMonthButton, previousMonthButton);
                        }
                        
                    }
                    
                    dayButton.addEventListener('change', function () {
                        checkDateRadioButtons(submitButton);
                    });
                });
            }
        });

        function checkDateRadioButtons(submitButton) {
            const dateRadioButtons = document.querySelectorAll('input[name="date"]');

            if (dateRadioButtons) {
                dateRadioButtons.forEach(dateButton => {
                    
                    if (dateButton.value == "{{ request('date') ?? (isset($appointment) ? Carbon::parse($appointment->app_start_time)->format('Y-m-d G:i') : null) }}") {
                        dateButton.checked = true;
                        submitButton.disabled = false;
                    }                    
                    
                    dateButton.addEventListener('change', function () {
                        const isAnyDatesChecked = Array.from(dateRadioButtons).some(radio => radio.checked);
                        if (submitButton) {
                            submitButton.disabled = !isAnyDatesChecked;
                        }
                    });
                });
            }
        }

        function checkNextMonth(calendarContainter, nextMonthButton, previousMonthButton) {
            calendarContainter.classList.add('-translate-x-1/2');
            nextMonthButton.setAttribute('disabled','');
            previousMonthButton.removeAttribute('disabled','');
        }

    </script>
</x-user-layout>