<x-user-layout title="My Appointments - ">
    <x-breadcrumbs :links="[
        'My Appointments' => route('my-appointments.index')
    ]"/>

    <div class="flex justify-between items-bottom mb-4">
        <h1 class="font-bold text-4xl">
            My Appointments
        </h1>
        <x-link-button :link="route('my-appointments.create')" role="createMain">Add New</x-link-button>
    </div>

    @forelse ($appointments as $appointment)
        <x-appointment-card :appointment="$appointment" class="mb-4"/>
    @empty
        <div>You have no upcoming appointments!</div>
    @endforelse

    <div class="mb-4">
        {{$appointments->links()}}
    </div>
</x-user-layout>