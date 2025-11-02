@php
    $view = $view ?? 'barber';

    $steps = [true,true,true,false];

    if($view == 'admin') {
        $breadcrumbLinks = [
            'Admin dashboard' => route('admin'),
            'Bookings' => route('bookings.index'),
            'New booking' => ''
        ];
        $submitLink = route('bookings.create');
    } else {
        $breadcrumbLinks = [
            'Bookings' => route('appointments.index'),
            'Service' => route('appointments.create.service',['service_id' => $service->id]),
            'Date & time' => route('appointments.create.date', ['service_id' => $service->id, 'date' => request('date')]),
            'Customer' => ''
        ];
        $submitLink = route('appointments.create.customer');
    }
@endphp

<x-user-layout title="New booking" currentView="{{ $view }}">
    <x-breadcrumbs :links="$breadcrumbLinks"/>
    
    <div class="flex justify-between">
        <x-headline class="mb-4 blue-300">Select your customer</x-headline>
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
            <h2 class="font-bold text-xl max-sm:text-lg mb-4">For registered customers</h2>
            <form method="GET" action="{{ $submitLink }}">
                <div class="flex gap-2 mb-4">
                    <x-input-field name="query" placeholder="Search users..." value="{{ old('query') ?? request('query') }}" class="w-full" />

                    <x-link-button link="{{ $submitLink }}?service_id={{ $service->id }}&date={{ request('date') }}&comment={{ request('comment') }}" role="destroy">
                        <span class="max-sm:hidden">Clear</span>
                    </x-link-button>

                    <x-button role="search">
                        <span class="max-sm:hidden">Search</span>
                    </x-button>
                </div>

                <input type="hidden" name="service_id" value="{{ $service->id }}">
                <input type="hidden" name="date" value="{{ request('date') }}">
                <input type="hidden" name="comment" value="{{ request('comment') }}">

                <p class="text-slate-500 text-justify">
                    Using this search bar you can narrow down the registered customers to find the one you're looking for. You can search here by name, email address and telephone number.
                </p>
            </form>
        </x-card>

        <x-card class="max-md:order-1">
            <h2 class="font-bold text-xl max-sm:text-lg mb-4">For accountless customers</h2>

            <p class="text-justify text-slate-500 mb-4">
                If your customer is not registered yet, then please click on the 'Continue' button below. You can enter their first name and email (if they want to share it with you). This can be useful for walk-in guests as well.
            </p>

            <x-link-button role="active" :link="route('appointments.create.confirm',['service_id' => $service->id, 'date' => request('date'), 'comment' => request('comment')])">
                Continue
            </x-link-button>
        </x-card>
    </div>

    <h2 class="font-bold text-2xl max-md:text-xl mb-4">Search results</h2>

    @if ($users->count() > 0)
        <x-card class="mb-4">
            <ul class="flex flex-col gap-4">
                @foreach ($users as $user)
                    <li class="flex max-sm:flex-col gap-2 justify-between {{ !$loop->last ? 'border-b-2 pb-4' : '' }}">
                        <div>
                            <h3 class="font-bold text-xl max-md:text-base mb-1">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </h3>
                            <p class="text-slate-500">
                                Email: <a href="mailto:{{ $user->email }}" class="text-blue-700 hover:underline">{{ $user->email }}</a>
                            </p>
                            <p class="text-slate-500">
                                Tel: <a href="tel:{{ $user->tel_number }}" class="text-blue-700 hover:underline">{{ $user->tel_number }}</a>
                            </p>
                        </div>
                        <x-link-button :link="$view == 'admin' ? route('bookings.create.barber.service',['user_id' => $user->id]) : route('appointments.create.confirm',['user_id' => $user->id, 'service_id' => $service->id, 'date' => request('date'), 'comment' => request('comment')])" role="ctaMain" :maxHeightFit="true">Select customer</x-link-button>
                    </li>
                @endforeach
            </ul>
        </x-card>
    @else
        <x-empty-card class="mb-4">
            There aren't any users with matching properties
        </x-empty-card>
    @endif
    

    <div @class(['mb-4' => $users->count() == 10])>
        {{ $users->appends($_GET)->links() }}
    </div>

</x-user-layout>