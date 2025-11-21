@php
    $view = $view ?? 'barber';
@endphp

<x-user-layout title="{{ __('barber.booking') . ' #' . $appointment->id }}" currentView="{{ $view }}">

    <div class="flex justify-between items-end align-bottom mb-4">
        <div>
            <x-breadcrumbs :links="$view == 'admin' ? [
                __('home.admin_dashboard') => route('admin'),
                __('home.bookings') => route('bookings.index'),
                __('barber.booking') . ' #' . $appointment->id => ''
                ] : [
                __('home.bookings') => route('appointments.index'),
                __('barber.booking') . ' #' . $appointment->id => ''
            ]"/>
            <x-headline>{{$appointment->user->first_name . __('barber.s1s_booking')}}</x-headline>
        </div>
        <div>
            <x-link-button :link="$view == 'admin' ? route('bookings.create') : route('appointments.create')"  role="createMain">
                <span class="max-sm:hidden">
                    {{ __('appointments.new_booking') }}
                </span>
            </x-link-button>
        </div>
        
    </div>

    <x-appointment-card :appointment="$appointment" access="{{ $view }}" class="mb-8">
        <div class="text-base max-sm:text-sm text-slate-500">
            {{ __('appointments.comment') }}:
            @if (!$appointment->comment)
                <span class="italic">
                    {{ __('barber.no_comments_from1') . $appointment->user->first_name . __('barber.no_comments_from2') }}
                </span>
            @else
                {{ $appointment->comment }}
            @endif
        </div>
    </x-appointment-card>

    <h2 class="font-bold text-2xl max-md:text-xl mb-4">
        {{$appointment->user->first_name . __('barber.s1s_details')}}
    </h2>

    @if ($appointment->user->hasEmail())
        <x-card class="flex max-md:flex-col gap-4 mb-4">
            <div class="text-base max-md:text-sm flex-1">
                <div class="mb-4 pb-4 border-b-2">
                    <h3 class="font-bold text-xl max-md:text-lg mb-2">
                        {{ __('home.contact') }}
                    </h3>
                    <ul>
                        <li>
                            {{ __('auth.tel_number') }}:
                            @if ($appointment->user->tel_number)
                            <a href="tel:{{ $appointment->user->tel_number }}" class="text-blue-700 hover:underline">{{ $appointment->user->tel_number }}</a>
                            @else
                                <span class="italic">{{ __('barber.not_given_yet') }}</span>
                            @endif
                        </li>
                        
                        <li>
                            {{ __('auth.email') }}: <a href="mailto:{{ $appointment->user->email }}" class="text-blue-700 hover:underline">{{ $appointment->user->email }}</a>
                        </li>                
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-xl max-md:text-lg mb-2">{{ __('barber.account') }}</h3>

                    @if ($appointment->user->isRegistered())
                        <ul>
                            <li>
                                {{ __('auth.date_of_birth') }}: <span @class(['italic' => !$appointment->user->date_of_birth])>{{ $appointment->user->date_of_birth ? \Carbon\Carbon::parse($appointment->user->date_of_birth)->format('Y-m-d') . ' (' .  floor(\Carbon\Carbon::parse($appointment->user->date_of_birth)->diffInYears(now())) . ' ' . __('barber.years_old') . ')' : __('barber.not_given_yet')}}</span>
                            </li>
                            <li>
                                {{ __('barber.account_created') }}: {{ \Carbon\Carbon::parse($appointment->user->created_at)->format('Y-m-d G:i') }}
                            </li>
                            <li>
                                {{ __('barber.email_verified') }}: <span @class(['italic' => !$appointment->user->email_verified_at])>
                                    {{ $appointment->user->email_verified_at ? \Carbon\Carbon::parse($appointment->user->email_verified_at)->format('Y-m-d G:i') : __('users.not_verified_yet') }}
                                </span>
                            </li>
                            
                            @if ($view == 'admin')
                            <li class="mt-2">
                                <a href="{{ route('customers.show',$appointment->user) }}" class="text-blue-700 hover:underline font-bold">
                                    {{ __('barber.view2') . $appointment->user->first_name . __('barber.s1s_profile') }}
                                </a>
                            </li>
                            @endif
                        </ul>
                    @else
                        <p class="italic mb-2">{{ $appointment->user->first_name . __('barber.not_reg_p1') }}</p>
                        <p class="italic">{{ __('barber.not_reg_p2') }}</p>
                    @endif
                </div>  
            </div>

            <div class="border-l-2 max-md:border-l-0 max-md:border-b-2"></div>

            <div class="text-base max-md:text-sm flex-1">
                <h3 class="font-bold text-xl max-md:text-lg mb-2">{{ __('home.bookings') }}</h3>

                <ul>
                    <li>{{ __('barber.upcoming_bookings') . ': ' . $upcoming }}</li>
                    <li>{{ __('barber.previous_bookings') . ': ' . $previous }}</li>
                    <li>{{ __('barber.cancelled_bookings') . ': ' . $cancelled }}</li>
                    <li>{{ __('barber.fav_barber') . ': ' . $favBarber->getName() . ' (' . $numBarber . ')' }}</li>
                    <li>{{ __('barber.fav_service') . ': ' . $favService->getName() . ' (' . $numService . ')' }}</li>

                    @if ($view == 'admin')
                    <li class="mt-2">
                        <a href="{{ route('bookings.index',['user' => $appointment->user]) }}" class="text-blue-700 hover:underline font-bold">
                            {{ __('barber.view2') . $appointment->user->first_name . __('barber.s1s_bookings') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </x-card>
    @else
        <x-empty-card class="mb-4">
            <p class="text-lg max-md:text-base mb-4">
                {{ __('barber.walk_in_p1a') }}
                <span class="font-bold">{{ __('barber.walk_in_p1b') }}</span>
                {{ __('barber.walk_in_p1c') }}
            </p>
            <p class="max-md:mb-4">
                {{ __('barber.walk_in_p2a') . $appointment->user->first_name . __('barber.walk_in_p2b') }}
            </p>
            <p>{{ __('barber.not_reg_p2') }}</p>
        </x-empty-card>
    @endif
    
</x-user-layout>