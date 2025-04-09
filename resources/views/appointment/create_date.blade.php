<x-user-layout title="New Booking - " currentView="barber">
    <x-breadcrumbs :links="[
        'Bookings' => route('appointments.index'),
        'New Booking' => route('appointments.create'),
        'Select a Service' => route('appointments.create.service'),
        'Select a Date' => ''
    ]"/>

    <h1 class="font-extrabold text-4xl mb-4">Select a Date</h1>
    <x-card class="mb-4">
        <div class="flex flex-col justify-between gap-2">
            <div>
                <h2 class="font-bold text-2xl mb-2">{{ $user->first_name }}'s Booking</h2>
                <h3 class="font-medium text-lg">
                    <a href="{{route('appointments.create.service')}}">
                        {{$service->name}}
                        •
                        {{$service->duration}} minutes
                        •
                        {{number_format($service->price,thousands_separator:' ')}} Ft
                    </a>
                </h3>
                <p class="font-medium text-base text-slate-500">
                    Barber: {{$barber->display_name ?? $barber->user->first_name}}
                </p>
            </div>
            <div>
                <form action="{{route('appointments.store',[
                    'user_id' => $user,
                    'service_id' => $service
                ])}}" method="POST">
                @csrf
                <label for="comment">Wanna leave some comments for this appointment? Share with us below!</label>
                <textarea name="comment" id="comment" class="h-20 w-full border rounded-md border-slate-300 resize-none p-4"">{{ old('comment') }}</textarea>
            </div>
        </div>
    </x-card>

    <x-card class="text-center mb-8">

        <div id="earliestDates" class=" -translate-y-10"></div>
        <h2 class="font-bold text-2xl mb-8">Earliest available dates</h2>

        <div class="grid grid-cols-2 max-lg:grid-cols-1 mb-8 gap-4">
            <div>
                <h3 class="font-medium text-lg mb-2">Today ({{today()->format('l')}})</h3>
                <div class="flex flex-wrap gap-2 justify-center">
                    @if ($dates[0] ?? false)
                        @forelse ($dates[0] as $date)
                                <x-button :value="$date->format('Y-m-d G:i')" name="date">
                                    {{$date->format('G:i')}}
                                </x-button>
                        @empty
                            <p>There are no available dates for this day</p>
                        @endforelse
                    @else
                        <p>There are no available dates for this day</p>
                    @endif
                </div>
            </div>

            <div>
                <h3 class="font-medium text-lg mb-2">Tomorrow ({{today()->addDay()->format('l')}})</h3>
                <div class="flex flex-wrap gap-2 justify-center">
                    @if ($dates[1] ?? false)
                        @forelse ($dates[1] as $date)
                                <x-button :value="$date->format('Y-m-d G:i')" name="date">
                                    {{$date->format('G:i')}}
                                </x-button>
                        @empty
                            <p>There are no available dates for this day</p>
                        @endforelse
                    @else
                        <p>There are no available dates for this day</p>
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
                                <div class="flex flex-wrap gap-2 justify-center">
                                    @forelse ($times as $time)
                                            <x-button :value="$time->format('Y-m-d G:i')" name="date">
                                                {{$time->format('G:i')}}
                                            </x-button>
                                    @empty
                                        <p>There are no available dates for this day</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif
                    @endforeach
                    </form>
                </div>
            </div>
        </div>

    </x-card>
</x-user-layout>