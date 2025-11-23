@php
    $view = $view ?? 'barber';

    $steps = [true,true,true,false];

    if($view == 'admin') {
        $breadcrumbLinks = [
            __('home.admin_dashboard') => route('admin'),
            __('home.bookings') => route('bookings.index'),
            __('appointments.barber_and_service') => route('bookings.create.barber.service',['service_id' => $service->id, 'barber_id' => $barber->id]),
            __('appointments.date_and_time') => route('bookings.create.date', ['service_id' => $service->id, 'barber_id' => $barber->id, 'date' => request('date'), 'comment' => request('comment')]),
            __('barber.customer') => ''
        ];
        $submitLink = route('bookings.create.customer', ['service_id' => $service->id, 'barber_id' => $barber->id, 'date' => request('date'), 'comment' => request('comment')]);
        $confirmRoute = route('bookings.create.confirm',['service_id' => $service->id, 'barber_id' => $barber->id, 'date' => request('date'), 'comment' => request('comment')]);
    } else {
        $breadcrumbLinks = [
            __('home.bookings') => route('appointments.index'),
            __('appointments.service') => route('appointments.create.service',['service_id' => $service->id]),
            __('appointments.date_and_time') => route('appointments.create.date', ['service_id' => $service->id, 'date' => request('date'), 'comment' => request('comment')]),
            __('barber.customer') => ''
        ];
        $submitLink = route('appointments.create.customer');
        $confirmRoute = route('appointments.create.confirm',['service_id' => $service->id, 'date' => request('date'), 'comment' => request('comment')]);
    }
@endphp

<x-user-layout title="{{ __('appointments.new_booking') }}" currentView="{{ $view }}">
    <x-breadcrumbs :links="$breadcrumbLinks"/>
    
    <div class="flex justify-between">
        <x-headline class="mb-4 blue-300">
            {{ __('barber.select_your_customer') }}
        </x-headline>
        <div class="w-16 flex gap-1">                
            @foreach ($steps as $step)
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="40" stroke="#93c5fd" stroke-width="6" fill="{{ $step ? '#93c5fd' : 'none' }}" />
                </svg>
            @endforeach
        </div>
    </div>
    
    <div class="grid grid-cols-2 max-md:grid-cols-1 gap-4 mb-8">
        <x-card class="max-md:order-2">
            <h2 class="font-bold text-xl max-sm:text-lg mb-4">
                {{ __('barber.for_registered_customers') }}
            </h2>
            <form method="GET" action="{{ $submitLink }}">
                <div class="flex gap-2 mb-4">
                    <x-input-field name="query" :placeholder="__('barber.search_users')" value="{{ old('query') ?? request('query') }}" class="w-full" />

                    <x-link-button :link="$submitLink" role="destroy">
                        <span class="max-sm:hidden">{{ __('barber.clear') }}</span>
                    </x-link-button>

                    <x-button role="search">
                        <span class="max-sm:hidden">{{ __('barber.search') }}</span>
                    </x-button>
                </div>

                <input type="hidden" name="service_id" value="{{ $service->id }}">
                <input type="hidden" name="date" value="{{ request('date') }}">
                <input type="hidden" name="comment" value="{{ request('comment') }}">

                @if ($view == 'admin')
                    <input type="hidden" name="barber_id" value="{{ $barber->id }}">
                @endif

                <p class="text-slate-500 text-justify">
                    {{ __('barber.registered_p') }}
                </p>
            </form>
        </x-card>

        <x-card class="max-md:order-1">
            <h2 class="font-bold text-xl max-sm:text-lg mb-4">{{ __('barber.for_accountless_customers') }}</h2>

            <p class="text-justify text-slate-500 mb-4">
                {{ __('barber.accountless_p') }}
            </p>

            <x-link-button role="active" :link="$confirmRoute">
                {{ __('barber.continue') }}
            </x-link-button>
        </x-card>
    </div>

    <h2 class="font-bold text-2xl max-md:text-xl mb-4">
        {{ __('barber.search_results') }}
    </h2>

    @if ($users->count() > 0)
        <x-card class="mb-4">
            <ul class="flex flex-col gap-4">
                @foreach ($users as $user)
                    <li class="flex max-sm:flex-col gap-2 justify-between {{ !$loop->last ? 'border-b-2 pb-4' : '' }}">
                        <div>
                            <h3 class="font-bold text-xl max-md:text-base mb-1">
                                {{ $user->getFullName() }}
                            </h3>
                            <p class="text-slate-500">
                                Email: <a href="mailto:{{ $user->email }}" class="text-blue-700 hover:underline">{{ $user->email }}</a>
                            </p>
                            <p class="text-slate-500">
                                Tel: <a href="tel:{{ $user->tel_number }}" class="text-blue-700 hover:underline">{{ $user->tel_number }}</a>
                            </p>
                        </div>
                        <x-link-button :link="$confirmRoute . '&user_id=' . $user->id" role="ctaMain" :maxHeightFit="true">
                            {{ __('barber.select_customer') }}
                        </x-link-button>
                    </li>
                @endforeach
            </ul>
        </x-card>
    @else
        <x-empty-card class="mb-4">
            {{ __('barber.empty_users') }}
        </x-empty-card>
    @endif
    

    <div @class(['mb-4' => $users->count() == 10])>
        {{ $users->appends($_GET)->links() }}
    </div>

</x-user-layout>