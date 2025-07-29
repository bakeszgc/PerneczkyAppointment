@php
    use Carbon\Carbon;
@endphp

<x-user-layout title="Book an Appointment - ">
    <x-breadcrumbs :links="[
        'Book an Appointment' => route('my-appointments.create'),
        'Select a Barber and a Service' => route('my-appointments.create.barber.service',['service_id' => $service->id, 'barber_id' => $barber->id]),
        'Select a Date' => ''
    ]"/>

    <h1 class="font-extrabold text-4xl mb-4">Select your Date</h1>

    <form action="{{route('my-appointments.store',[
        'barber_id' => $barber,
        'service_id' => $service
    ])}}" method="POST">
        @csrf

        <x-card class="mb-4">
            <div class="flex justify-between gap-4">
                <div class="flex flex-col justify-between">
                    <div>
                        <h2 class="font-bold text-2xl mb-2">Your Appointment</h2>
                        <h3 class="font-medium text-lg">
                            <a href="{{route('my-appointments.create.barber.service',['barber_id' => $barber->id])}}">
                                {{$service->name}}
                                •
                                {{$service->duration}} minutes
                                •
                                {{number_format($service->price,thousands_separator:' ')}} Ft
                            </a>
                        </h3>
                        <p class="font-medium text-base text-slate-500">
                            <a href="{{route('my-appointments.create.barber.service',['barber_id' => $barber->id])}}">
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

        </script>

        <div class="mb-8">
            <x-button role="ctaMain" :full="true" id="ctaButton" :disabled="false">Book Appointment</x-button>
        </div>
    </form>
</x-user-layout>