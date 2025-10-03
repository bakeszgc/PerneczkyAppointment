@php
    $view = $view ?? 'barber';
@endphp

<x-user-layout title="{{$appointment->user->first_name}}'s booking" currentView="{{ $view }}">

    

    <div class="flex justify-between items-end align-bottom mb-4">
        <div>
            <x-breadcrumbs :links="$view == 'admin' ? [
                'Admin dashboard' => route('admin'),
                'Bookings' => route('bookings.index'),
                'Booking #' . $appointment->id => ''
                ] : [
                'Bookings' => route('appointments.index'),
                'Booking #' . $appointment->id => ''
            ]"/>
            <x-headline>{{$appointment->user->first_name}}'s booking</x-headline>
        </div>
        <div>
            <x-link-button :link="$view == 'admin' ? route('bookings.create') : route('appointments.create')"  role="createMain">
                <span class="max-sm:hidden">New&nbsp;booking</span>
            </x-link-button>
        </div>
        
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

    <h2 class="font-bold text-2xl max-md:text-xl mb-4">{{$appointment->user->first_name}}'s details</h2>

    <x-card class="flex max-md:flex-col gap-4 mb-4">
        <div class="text-base max-md:text-sm flex-1">
            <div class="mb-4 pb-4 border-b-2">
                <h3 class="font-bold text-xl max-md:text-lg mb-2">Contact</h3>
                <ul>
                    <li>
                        Telephone number: <a href="tel:{{ $appointment->user->tel_number }}" class="text-blue-700 hover:underline">{{ $appointment->user->tel_number }}</a>
                    </li>
                    <li>
                        Email address: <a href="mailto:{{ $appointment->user->email }}" class="text-blue-700 hover:underline">{{ $appointment->user->email }}</a>
                    </li>                
                </ul>
            </div>

            <div>
                <h3 class="font-bold text-xl max-md:text-lg mb-2">Account</h3>
                <ul>                
                    <li>
                        Date of birth: {{ \Carbon\Carbon::parse($appointment->user->date_of_birth)->format('Y-m-d') }} ({{ floor(\Carbon\Carbon::parse($appointment->user->date_of_birth)->diffInYears(now())) }} years old)
                    </li>
                    <li>
                        Account created: {{ \Carbon\Carbon::parse($appointment->user->created_at)->format('Y-m-d G:i') ?? 'Not created yet' }}
                    </li>
                    <li>
                        Email verified: {{ \Carbon\Carbon::parse($appointment->user->email_verified_at)->format('Y-m-d G:i') ?? 'Not verified yet' }}
                    </li>
                    
                    @if ($view == 'admin')
                    <li class="mt-2">
                        <a href="{{ route('customers.show',$appointment->user) }}" class="text-blue-700 hover:underline font-bold">
                            View {{ $appointment->user->first_name }}'s profile
                        </a>
                    </li>
                    @endif
                </ul>
            </div>  
        </div>

        <div class="border-l-2 max-md:border-l-0 max-md:border-b-2"></div>

        <div class="text-base max-md:text-sm flex-1">
            <h3 class="font-bold text-xl max-md:text-lg mb-2">Bookings</h3>

            <ul>
                <li>Upcoming bookings: {{ $upcoming }}</li>
                <li>Previous bookings: {{ $previous }}</li>
                <li>Cancelled bookings: {{ $cancelled }}</li>
                <li>Favourite barber: {{ $favBarber->getName() }} ({{ $numBarber }})</li>
                <li>Favourite service: {{ $favService->name }} ({{ $numService }})</li>

                @if ($view == 'admin')
                <li class="mt-2">
                    <a href="{{ route('bookings.index',['user' => $appointment->user]) }}" class="text-blue-700 hover:underline font-bold">
                        View {{ $appointment->user->first_name }}'s bookings
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </x-card>
    
</x-user-layout>