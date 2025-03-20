<x-user-layout title="{{$type}} Appointments - " currentView="barber">

    <x-breadcrumbs :links="[
        'Appointments' => route('appointments.index'),
        $type => ''
    ]"/>

    <div class="flex justify-between items-bottom mb-4">
        <h1 class="font-extrabold text-4xl">
            @if ($type != null)
                {{$type}}
            @endif
            Appointments
        </h1>
        <x-link-button :link="route('appointments.create')" role="createMain">Add New</x-link-button>
    </div>

    <div class="grid grid-cols-4 gap-2 mb-4 p-2 rounded-md bg-slate-300 text-center text-lg font-bold">
        <a href="{{ route('appointments.index') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'All'])>All</a>
        <a href="{{ route('appointments.upcoming') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Upcoming'])>Upcoming</a>
        <a href="{{ route('appointments.previous') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Previous'])>Previous</a>
        <a href="{{ route('appointments.cancelled') }}" @class(['p-2 rounded-md hover:bg-white transition-all', 'bg-white' => $type == 'Cancelled'])>Cancelled</a>
    </div>

    <x-card class="mb-4">
        <div class=" relative">
            
            <div class="grid grid-cols-7 text-center mb-4 ml-16">

                @for ($i = 1; $i<=7; $i++)
                    <div class="flex items-center justify-center gap-1 max-lg:flex-col">
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
                @for ($i = 10; $i<=20; $i++)
                    <div class="text-slate-500 border-slate-300 border-t mb-8">
                        {{ $i }}:00
                    </div>
                @endfor
            </div>

            <div style="{{ "position: absolute; width: 109.42px;
            top: " . 53/60 * \Carbon\Carbon::parse($test->app_start_time)->format('G')*60 + \Carbon\Carbon::parse($test->app_start_time)->format('i') -486 .
            "px; left: " . \Carbon\Carbon::parse($test->app_start_time)->format('N') * 109.42-45.42 . "px;
            height: " . \Carbon\Carbon::parse($test->app_start_time)->diffInMinutes(\Carbon\Carbon::parse($test->app_end_time)) / 60 * 53 .
            "px;" }}">
                <div class="bg-blue-200 hover:bg-blue-300 transition-all border text-blue-600 font-bold h-full m-0.5 px-1 rounded-md">
                    {{ \Carbon\Carbon::parse($test->app_start_time)->format('G:i') }}
                    <span class="font-normal">
                        {{ $test->user->first_name }}
                    </span>
                    
                </div>
            </div>
            
        </div>
    </x-card>
    
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