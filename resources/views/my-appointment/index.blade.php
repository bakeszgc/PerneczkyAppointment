<x-user-layout title="{{ __('home.my_appointments') }}">

    <div class="flex justify-between items-end align-bottom mb-4">
        <div>
            <x-breadcrumbs :links="[
                __('home.my_appointments') => route('my-appointments.index')
            ]"/>
            <x-headline>
                {{ strtolower($type) == 'upcoming' ? __('appointments.my_upcoming_appointments') : __('appointments.my_previous_appointments') }}
            </x-headline>
        </div>
        <div>
            <x-link-button :link="route('my-appointments.create')" role="createMain">
                <span class="max-sm:hidden">
                    {{ __('appointments.new_appointment') }}
                </span>
            </x-link-button>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-2 mb-4 p-2 max-md:p-1 rounded-md bg-slate-300 text-center text-lg max-md:text-base font-bold">
        <a href="{{ route('my-appointments.index') }}" @class(['p-2 rounded-md hover:bg-white transition-all' => $type == 'Previous', 'p-2 rounded-md bg-white' => $type == 'Upcoming'])>
            {{ __('appointments.upcoming') }}
        </a>
        <a href="{{ route('my-appointments.index.previous') }}" @class(['p-2 rounded-md hover:bg-white transition-all' => $type == 'Upcoming', 'p-2 rounded-md bg-white' => $type == 'Previous'])>
            {{ __('appointments.previous') }}
        </a>
    </div>

    @forelse ($appointments as $appointment)
        <x-appointment-card :appointment="$appointment" :showDetails="true" access="user" class="mb-4"/>
    @empty
        <x-empty-card>
            <p class="text-lg max-md:text-base font-medium">
                {{ $appCount == 0 ? __('appointments.first_time') : __('appointments.no_'.lcfirst($type)) }}
            </p>
            <a href="{{ route('my-appointments.create') }}" class=" text-blue-700 hover:underline">
                {{ $appCount == 0 ? __('appointments.first_time_book') : __('appointments.why_not_book') }}
            </a>
        </x-empty-card>
    @endforelse

    <div class="mb-8">
        {{$appointments->links()}}
    </div>
</x-user-layout>