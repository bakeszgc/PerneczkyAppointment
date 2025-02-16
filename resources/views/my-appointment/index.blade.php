<x-user-layout title="My Appointments - ">
    <x-breadcrumbs :links="[
        'My Appointments' => route('my-appointments.index')
    ]"/>

    <div class="flex justify-between items-bottom mb-4">
        <h1 class="font-extrabold text-4xl">
            My {{ $type }} Appointments
        </h1>
        <x-link-button :link="route('my-appointments.create')" role="createMain">Book now</x-link-button>
    </div>

    <div class="flex gap-4 mb-4">
        <x-link-button :link="route('my-appointments.index')" :full="true">
            Upcoming
        </x-link-button>
        <x-link-button :link="route('my-appointments.index.previous')" :full="true">
            Previous
        </x-link-button>
    </div>

    @forelse ($appointments as $appointment)
        <x-appointment-card :appointment="$appointment" class="mb-4"/>
    @empty
        <div class="text-center w-full rounded-md p-8 border border-dashed border-slate-500">
            <p class="text-lg font-medium">You don't have any {{ lcfirst($type) }} appointments!</p>
            <a href="{{ route('my-appointments.create') }}" class=" text-blue-700 hover:underline">Why not booking one right now?</a>
        </div>
    @endforelse

    <div class="mb-4">
        {{$appointments->links()}}
    </div>
</x-user-layout>