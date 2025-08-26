@php
    $view = $view ?? 'barber';
@endphp

<x-user-layout title="{{$appointment->user->first_name}}'s Booking" currentView="{{ $view }">

    <x-breadcrumbs :links="$view == 'admin' ? [
            'Admin Dashboard' => route('admin'),
            'Bookings' => route('bookings.index'),
            $appointment->user->first_name . '\'s Booking' => ''
        ] : [
        'Bookings' => route('appointments.index'),
        $appointment->user->first_name . '\'s Booking' => ''
    ]"/>

    <div class="flex justify-between items-end mb-4">
        <x-headline>{{$appointment->user->first_name}}'s Booking</x-headline>
        <x-link-button :link="$view == 'admin' ? route('bookings.create') : route('appointments.create')"  role="createMain">New&nbsp;Booking</x-link-button>
    </div>

    <x-appointment-card :appointment="$appointment" access="{{ $view }}" class="mb-8">
        <div class="text-base max-sm:text-sm text-slate-500">
            Comment:
            @if (!$appointment->comment)
                <span class="italic">No comments from {{ $appointment->user->first_name }}.</span>
            @else
                {{ $appointment->comment }}
            @endif
        </div>
    </x-appointment-card>

    <h2 class="font-bold text-2xl mb-4">{{$appointment->user->first_name}}'s Details</h2>

    <x-card class="flex max-md:flex-col gap-4 mb-4">
        <div class="text-base flex-1">
            <h3 class="font-bold text-xl mb-2">Contact</h3>
            <p>
                Telephone number: <a href="tel:{{ $appointment->user->tel_number }}" class="text-blue-700 hover:underline">{{ $appointment->user->tel_number }}</a>
            </p>
            <p>
                Email address: <a href="mailto:{{ $appointment->user->email }}" class="text-blue-700 hover:underline">{{ $appointment->user->email }}</a>
            </p>

            <h3 class="font-bold text-xl mb-2 mt-4">Account</h3>
            <p>
                Date of birth: {{ \Carbon\Carbon::parse($appointment->user->date_of_birth)->format('Y.m.d.') }} ({{ floor(\Carbon\Carbon::parse($appointment->user->date_of_birth)->diffInYears(now())) }} years old)
            </p>
            <p>
                Account created: {{ \Carbon\Carbon::parse($appointment->user->created_at)->format('Y.m.d. G:i') ?? 'Not created yet' }}
            </p>
            <p>
                Email verified: {{ \Carbon\Carbon::parse($appointment->user->email_verified_at)->format('Y.m.d. G:i') ?? 'Not verified yet' }}
            </p>
        </div>
        <div class="border-l border-slate-300 max-md:border-0"></div>
        <div class="text-base flex-1">
            <h3 class="font-bold text-xl mb-2">Bookings</h3>
            <p>Upcoming bookings: {{ $upcoming }}</p>
            <p>Previous bookings: {{ $previous }}</p>
            <p>Cancelled bookings: {{ $cancelled }}</p>
            <p>Favourite barber: {{ $favBarber->display_name ?? $favBarber->user->first_name }} ({{ $numBarber }})</p>
            <p>Favourite service: {{ $favService->name }} ({{ $numService }})</p>
        </div>
    </x-card>
    
</x-layout>