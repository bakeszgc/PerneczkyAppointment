<x-user-layout title="My appointments">

    <div class="flex justify-between items-end align-bottom mb-4">
        <div>
            <x-breadcrumbs :links="[
                'My appointments' => route('my-appointments.index')
            ]"/>
            <x-headline>My {{ strtolower($type) }} appointments</x-headline>
        </div>
        <div>
            <x-link-button :link="route('my-appointments.create')" role="createMain">
                <span class="max-sm:hidden">New&nbsp;appointment</span>
            </x-link-button>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-2 mb-4 p-2 max-md:p-1 rounded-md bg-slate-300 text-center text-lg max-md:text-base font-bold">
        <a href="{{ route('my-appointments.index') }}" @class(['p-2 rounded-md hover:bg-white transition-all' => $type == 'Previous', 'p-2 rounded-md bg-white' => $type == 'Upcoming'])>Upcoming</a>
        <a href="{{ route('my-appointments.index.previous') }}" @class(['p-2 rounded-md hover:bg-white transition-all' => $type == 'Upcoming', 'p-2 rounded-md bg-white' => $type == 'Previous'])>Previous</a>
    </div>

    @forelse ($appointments as $appointment)
        <x-appointment-card :appointment="$appointment" :showDetails="true" access="user" class="mb-4"/>
    @empty
        <x-empty-card>
            <p class="text-lg font-medium">You don't have any {{ lcfirst($type) }} appointments!</p>
            <a href="{{ route('my-appointments.create') }}" class=" text-blue-700 hover:underline">Why not booking one right now?</a>
        </x-empty-card>
    @endforelse

    <div class="mb-8">
        {{$appointments->links()}}
    </div>
</x-user-layout>