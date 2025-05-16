<x-user-layout title="{{$type}} Bookings - " currentView="barber">

    <x-breadcrumbs :links="[
        'Bookings' => route('appointments.index'),
        $type => ''
    ]"/>

    <div class="flex justify-between items-end mb-4">
        <x-headline>
            @if ($type != null)
                {{$type}}
            @endif
            Bookings
        </x-headline>
        
        <x-link-button :link="route('appointments.create')" role="createMain">New&nbsp;Booking</x-link-button>
    </div>

    <div class="grid grid-cols-4 max-sm:grid-cols-2 gap-2 mb-4 p-2 rounded-md bg-slate-300 text-center text-lg font-bold">
        <a href="{{ route('appointments.index') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'All'])>All</a>
        <a href="{{ route('appointments.upcoming') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Upcoming'])>Upcoming</a>
        <a href="{{ route('appointments.previous') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Previous'])>Previous</a>
        <a href="{{ route('appointments.cancelled') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Cancelled'])>Cancelled</a>
    </div>

    @if ($type === 'All')
        <x-card class="mb-4">
            <div class="relative">
                
                <div class="flex text-center mb-4">
                    <div class="w-1/8"></div>
                    @for ($i = 1; $i<=7; $i++)
                        <div class="flex items-center justify-center gap-1 max-lg:flex-col w-1/8">
                            <span class="text-slate-500">
                                {{ date('D', strtotime("Sunday + {$i} days")) }}
                            </span>
                            <span @class([
                                    'font-bold rounded-full p-1 transition-all' => true,
                                    'bg-blue-600 text-white hover:bg-blue-800' => today()->format('D') == date('D', strtotime("Sunday + {$i} days")),
                                    'hover:bg-slate-300' => today()->format('D') != date('D', strtotime("Sunday + {$i} days"))
                                ])>
                                {{ today()->format('D') == date('D', strtotime("Sunday + {$i} days")) ? today()->format('d') : today()->addDays($i - today()->format('N'))->format('d') }}
                            </span>

                        </div>
                    @endfor
                </div>

                <div>
                    @for ($i = 10; $i<=21; $i++)
                        <div class="text-slate-500 border-slate-300 border-t mb-8">
                            {{ $i }}:00
                        </div>
                    @endfor
                </div>

                @foreach ($calAppointments as $appointment)
                    <x-calendar-event :appointment="$appointment"/>
                @endforeach
                
                @if (now()->format('G') >= 10 && now()->format('G') <= 21)
                    <div style="position: absolute; width: 100%; height: 1px; background-color: blue; top: {{ 53/60 * (now()->format('G') * 60 + now()->format('i')) - 486 }}px;">
                    </div>
                @endif
                
            </div>
        </x-card>
    @endif
    
    @forelse ($appointments as $appointment)
        <x-appointment-card :appointment="$appointment" access="barber" class="mb-4"/>
    @empty
        <div class="text-center w-full rounded-md p-8 border border-dashed border-slate-500">
            <p class="text-lg font-medium">You don't have any {{ lcfirst($type) }} appointments!</p>
            <a href="{{ route('appointments.create') }}" class=" text-blue-700 hover:underline">Add a new booking here for one of your clients!</a>
        </div>
    @endforelse

    <div class="mb-4">
        {{$appointments->links()}}
    </div>
    
</x-user-layout>