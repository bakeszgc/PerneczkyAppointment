@php
    use Carbon\Carbon;

    $view = $view ?? 'user';

    switch($view) {
        case 'user':
            $serviceLink = route('my-appointments.create.barber.service',['service_id' => $service->id, 'barber_id' => $barber->id]);

            $storeLink = route('my-appointments.store',['barber_id' => $barber->id, 'service_id' => $service->id]);

            $breadcrumbLinks = [
                'Book an Appointment' => route('my-appointments.create'),
                'Select a Barber and a Service' => $serviceLink,
                'Select a Date' => ''
            ];
            
            break;

        case 'barber':
            $serviceLink = route('appointments.create.service',['service_id' => $service->id, 'user_id' => $user->id]);
            $storeLink = route('appointments.store',['service_id' => $service->id, 'user_id' => $user->id]);

            $breadcrumbLinks = [
                'Bookings' => route('appointments.index'),
                'New Booking' => route('appointments.create'),
                'Select a Service' => $serviceLink,
                'Select a Date' => ''
            ];
            
            break;

        case 'admin':
            $serviceLink = route('bookings.create.barber.service',['user_id' => $user->id,'barber_id' => $barber->id, 'service_id' => $service->id]);
            $storeLink = route('bookings.store',['service_id' => $service->id, 'user_id' => $user->id, 'barber_id' => $barber->id]);

            $breadcrumbLinks = [
                'Admin Dashboard' => route('admin'),
                'Bookings' => route('bookings.index'),
                'Select a Barber and a Service' => $serviceLink,
                'Select a Date' => ''
            ];
            break;
    }
@endphp

<x-user-layout title="{{ $view == 'user' ? 'New Appointment - ' : 'New Booking - ' }}" currentView="{{ $view }}">
    <x-breadcrumbs :links="$breadcrumbLinks"/>

    <h1 class="font-extrabold text-4xl mb-4">Select your Date</h1>

    <form action="{{$storeLink}}" method="POST">
        @csrf

        <x-card class="mb-4">
            <div class="flex justify-between gap-4">
                <div class="flex flex-col justify-between">
                    <div>
                        <h2 class="font-bold text-2xl mb-2">{{ $view == 'user' ? 'Your' : ($user->first_name . "'s")}} Appointment</h2>
                        <h3 class="font-medium text-lg">
                            <a href="{{ $serviceLink }}">
                                {{$service->name}}
                                •
                                {{$service->duration}} minutes
                                •
                                {{number_format($service->price,thousands_separator:' ')}} Ft
                            </a>
                        </h3>
                        <p class="font-medium text-base text-slate-500">
                            <a href="{{ $serviceLink }}">
                            Barber: {{$barber->getName()}}
                            </a>
                        </p>
                    </div>
                    <div>
                        <label for="comment">Wanna leave some comments for this appointment? Share with us below!</label>
                        <textarea name="comment" id="comment" class="h-20 w-full border rounded-md border-slate-300 resize-none p-4"">{{ old('comment') }}</textarea>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <img src="{{ $barber->getPicture() }}" alt="BarberPic" class=" rounded-md h-52 w-auto">
                </div>
            </div>
        </x-card>

        <x-card class="mb-4">
            <div x-data="appointmentCalendar()" x-init="init()" class="grid grid-cols-2 gap-8">
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
                            <h2 class="w-full text-center mb-4 text-xl font-bold">
                                Free timeslots on <span x-text="selectedDate"></span>
                            </h2>
                            
                            <template x-if="slotsByDate[selectedDate]?.length">
                                <div class="grid grid-cols-6 gap-2 text-center">
                                    <template x-for="(time, index) in slotsByDate[selectedDate]" :key="`${selectedDate}_${time}`">
                                        <label :for="`date_${selectedDate}_${time}`" class="font-semibold border-2 border-[#0018d5] text-[#0018d5] rounded-md p-2 cursor-pointer hover:bg-[#0018d5] hover:text-white has-[input:checked]:bg-[#0018d5] has[input:checked]:shadow-2xl has-[input:checked]:text-white transition-all">

                                            <span x-text="time"></span>

                                            <input
                                                type="radio"
                                                name="date"
                                                class="hidden"
                                                :id="`date_${selectedDate}_${time}`"
                                                :value="`${selectedDate} ${time}`"
                                                x-model="selectedTime"
                                            >
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
            <x-button role="ctaMain" :full="true" id="submitBtnForRadioBtns" :disabled="true">Book Appointment</x-button>
        </div>
    </form>

    <script>

        function appointmentCalendar() {
            return {
                selectedTime: null,
                selectedDate: null,
                slotsByDate: @json($availableSlotsByDate),

                init() {
                    this.selectedDate = Object.keys(this.slotsByDate)[0] || null;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {

            // NEXT MONTH + PREVIOUS MONTH BUTTONS
            const calendarContainter = document.getElementById('calendarContainter');
            const nextMonthButton = document.getElementById('nextMonthButton');
            const previousMonthButton = document.getElementById('previousMonthButton');

            nextMonthButton.addEventListener('click', () => {
                calendarContainter.classList.add('-translate-x-1/4');
                nextMonthButton.setAttribute('disabled','');
                previousMonthButton.removeAttribute('disabled','');
            });

            previousMonthButton.addEventListener('click', () => {
                calendarContainter.classList.remove('-translate-x-1/4');
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
                    dateButton.addEventListener('change', function () {
                        const isAnyDatesChecked = Array.from(dateRadioButtons).some(radio => radio.checked);
                        if (submitButton) {
                            submitButton.disabled = !isAnyDatesChecked;
                        }
                    });
                });
            }
        }

    </script>
</x-user-layout>