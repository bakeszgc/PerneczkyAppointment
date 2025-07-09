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
        
        <!-- <x-card class="text-center mb-8">

            <div id="earliestDates" class=" -translate-y-10"></div>
            <h2 class="font-bold text-2xl mb-8">Earliest available dates</h2>

            <div class="grid grid-cols-2 max-lg:grid-cols-1 mb-8 gap-8">
                <div>
                    <h3 class="font-medium text-lg mb-4">Today ({{today()->format('l')}})</h3>
                    <div class="grid grid-cols-6 gap-2">
                        @if ($dates[0] ?? false)
                            @forelse ($dates[0] as $date)
                                <label for="date_{{ $date->format('Y-m-d_G:i') }}" class="font-semibold border-2 border-[#0018d5] text-[#0018d5] rounded-md p-2 cursor-pointer hover:text-white hover:bg-[#0018d5] has-[input:checked]:bg-[#0018d5] has-[input:checked]:shadow-2xl has-[input:checked]:text-white transition-all">

                                    {{$date->format('G:i')}}

                                    <input type="radio" value="{{ $date->format('Y-m-d G:i') }}" name="date" id="date_{{ $date->format('Y-m-d_G:i') }}" class="hidden">
                                </label>
                            @empty
                                <p class="col-span-6">There are no available dates for this day</p>
                            @endforelse
                        @else
                            <p class="col-span-6">There are no available dates for this day</p>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="font-medium text-lg mb-4">Tomorrow ({{today()->addDay()->format('l')}})</h3>
                    <div class="grid grid-cols-6 gap-2">
                        @if ($dates[1] ?? false)
                            @forelse ($dates[1] as $date)
                                <label for="date_{{ $date->format('Y-m-d_G:i') }}" class=" font-semibold border-2 border-[#0018d5] text-[#0018d5] rounded-md p-2 cursor-pointer hover:bg-[#0018d5] hover:text-white has-[input:checked]:bg-[#0018d5] has-[input:checked]:shadow-2xl has-[input:checked]:text-white transition-all">

                                    {{$date->format('G:i')}}

                                    <input type="radio" value="{{ $date->format('Y-m-d G:i') }}" name="date" id="date_{{ $date->format('Y-m-d_G:i') }}" class="hidden">
                                </label>
                            @empty
                                <p class="col-span-6">There are no available dates for this day</p>
                            @endforelse
                        @else
                            <p class="col-span-6">There are no available dates for this day</p>
                        @endif
                    </div>
                </div>
            </div>

            <div x-data="{ showDates:false }">
                <div id="otherDates" class=" -translate-y-28"></div>
                <h2 class="font-bold text-lg mt-16 mb-8">
                    <span class="transition-all border border-blue-700 bg-blue-100 hover:bg-blue-300 rounded-md text-blue-800 p-4 cursor-pointer hover:drop-shadow-lg" @click="
                        showDates = !showDates;
                        $nextTick(() => document.getElementById(showDates === true ? 'otherDates' : 'earliestDates').scrollIntoView({ behavior: 'smooth' }))
                    ">
                        ⬇️ Dates for other days ⬇️
                    </span>
                </h2>

                <div x-show="showDates" x-transition>
                    <div class="grid grid-cols-4 max-lg:grid-cols-1 gap-4 gap-y-8 mt-12 mb-8">
                        @foreach ($dates as $day => $times)
                            @if ($day >= 2)
                                <div>
                                    <h3 class="font-medium text-lg mb-2">
                                        {{today()->addDays($day)->format('m.d.')}}
                                        ({{today()->addDays($day)->format('l')}})
                                    </h3>

                                    <div class="grid grid-cols-3 gap-2">
                                        @forelse ($times as $time)
                                            <label for="date_{{ $time->format('Y-m-d_G:i') }}" class=" font-semibold border-2 border-[#0018d5] text-[#0018d5] rounded-md p-2 cursor-pointer hover:bg-[#0018d5] hover:text-white has-[input:checked]:bg-[#0018d5] has-[input:checked]:shadow-2xl has-[input:checked]:text-white transition-all">

                                                {{$time->format('G:i')}}

                                                <input type="radio" value="{{ $time->format('Y-m-d G:i') }}" name="date" id="date_{{ $time->format('Y-m-d_G:i') }}" class="hidden">
                                            </label>
                                        @empty
                                            <p class="col-span-6">There are no available dates for this day</p>
                                        @endforelse
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="mb-4">
                        <h3 class="font-medium text-lg">None of these dates work for you?</h3>
                        <p>Check out <a href="{{route('my-appointments.create.barber.service',['service_id' => $service->id])}}" class=" text-blue-700 hover:underline">our other barbers</a> or feel free to <a href="https://perneczkybarbershop.hu/en.html#contact" class=" text-blue-700 hover:underline">contact us!</a></p>
                    </div>
                </div>
            </div>

        </x-card> -->

        <x-card class="mb-4">
            <div x-data="appointmentCalendar()" x-init="init()" class="grid grid-cols-2 gap-8">
                @php
                    //$startOfCurrentMonth = (new Carbon('2025-06-20'))->startOfMonth();
                    $startOfCurrentMonth = now()->startOfMonth();
                @endphp

                <div>
                    <div class="w-full text-center mb-4 text-xl font-bold">
                        <p>{{ $startOfCurrentMonth->format('F Y') }}</p>
                    </div>

                    <div class="grid grid-cols-7 gap-4 *:text-center">
                        <p>Mo</p>
                        <p>Tu</p>
                        <p>We</p>
                        <p>Th</p>
                        <p>Fr</p>
                        <p>Sa</p>
                        <p>Su</p>
                    </div>

                    <div class="grid grid-cols-7 gap-4">
                        @for ($i=0; $i < $startOfCurrentMonth->format('w')+6 % 7; $i++)
                            <p></p>
                        @endfor

                        @for ($i = 1; $i<= $startOfCurrentMonth->month(intval($startOfCurrentMonth->format('m')))->daysInMonth(); $i++)
                            <div>
                                @php
                                    $date = new Carbon($startOfCurrentMonth->format('Y-m-') . $i);
                                    //$isEnabled = $date >= today() && $date <= today()->addWeeks(2);
                                    $isEnabled = true;

                                    if (!array_key_exists($date->format('Y-m-d'),$availableSlotsByDate)) {
                                        $isEnabled = false;
                                    }
                                @endphp
                                
                                <label for="day_{{ $startOfCurrentMonth->format('m').$i }}" @class([
                                    'rounded-full w-8 h-8 m-auto flex items-center
                                    has-[input:checked]:bg-[#0018d5] has-[input:checked]:shadow-2xl has-[input:checked]:text-white',
                                    'hover:text-white hover:bg-blue-400 transition-all cursor-pointer' => $isEnabled,
                                    'text-slate-300' => !$isEnabled
                                    ])>

                                    <p class="m-auto w-fit">
                                        {{ $i }}
                                    </p>

                                    <input type="radio" name="day" id="day_{{ $startOfCurrentMonth->format('m').$i }}" value="{{ $startOfCurrentMonth->format('Y-m'). '-' .($i < 10 ? '0' : '') . $i }}" class="hidden" x-model="selectedDate">

                                </label>

                            </div>
                            
                        @endfor
                    </div>
                </div>

                <div>
                    <template x-if="selectedDate">
                        <div>
                            <h2 class="w-full text-center mb-4 text-xl font-bold">
                                Available timeslots on <span x-text="selectedDate"></span>
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
            <x-button role="ctaMain" :full="true" id="ctaButton">Book Appointment</x-button>
        </div>
    </form>
</x-user-layout>