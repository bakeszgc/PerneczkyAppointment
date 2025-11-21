<x-user-layout title="{{ __('barber.your_' . ($type !== 'All' ? (strtolower($type) . '_') : '') . 'timeoffs') }}" currentView="barber">

    <div class="flex justify-between items-end mb-4">
        <div>
            <x-breadcrumbs :links="[
                __('home.time_offs') => route('time-offs.index')
            ]"/>
            <x-headline>
                {{ __('barber.your_' . ($type !== 'All' ? (strtolower($type) . '_') : '') . 'timeoffs') }}
            </x-headline>
        </div>
        
        <div>
            <x-link-button :link="route('time-offs.create')" role="timeoffCreateMain">
                <span class="max-sm:hidden">
                    {{ __('barber.new_time_off') }}
                </span>
            </x-link-button>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-2 mb-4 p-2 max-sm:p-1 rounded-md bg-slate-300 text-center text-lg max-md:text-base font-bold">
        <a href="{{ route('time-offs.index') }}" @class(['p-2 rounded-md hover:bg-white transition-all' => $type != 'All', 'p-2 rounded-md bg-white' => $type == 'All'])>
            {{ __('barber.all') }}
        </a>
        <a href="{{ route('time-offs.upcoming') }}" @class(['p-2 rounded-md hover:bg-white transition-all' => $type != 'Upcoming', 'p-2 rounded-md bg-white' => $type == 'Upcoming'])>
            {{ __('barber.upcoming') }}
        </a>
        <a href="{{ route('time-offs.previous') }}" @class(['p-2 rounded-md hover:bg-white transition-all' => $type != 'Previous', 'p-2 rounded-md bg-white' => $type == 'Previous'])>
            {{ __('appointments.previous') }}
        </a>
    </div>

    @if ($type === 'All')
        <x-card class="mb-4">        
            <x-appointment-calendar :calAppointments="$calAppointments" :barber="auth()->user()->barber" :barbers="$barbers" />
        </x-card>
    @endif

    @forelse ($timeoffs as $timeoff)
        <x-time-off-card :appointment="$timeoff" :showDetails="true" class="mb-4"/>
    @empty
        <x-empty-card>
            <p class="text-lg max-md:text-base font-medium">
                {{ __('barber.no_' . ($type !== "All" ? (lcfirst($type) . '_') : '') . 'timeoffs') }}
            </p>
            <a href="{{ route('time-offs.create') }}" class=" text-green-700 hover:underline">
                {{ __('barber.set_one') }}
            </a>
        </x-empty-card>
    @endforelse

    <div class="mb-4">
        {{$timeoffs->links()}}
    </div>

</x-user-layout>