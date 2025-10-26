@php
    $view = $view ?? 'barber';

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
            'New booking' => ''
        ];
        $submitLink = route('appointments.create');
    }
@endphp

<x-user-layout title="New booking" currentView="{{ $view }}">
    <x-breadcrumbs :links="$breadcrumbLinks"/>
    <x-headline class="mb-4">
        Create a new booking
    </x-headline>
    
    <x-card class="mb-8">
        <h2 class="font-bold text-2xl max-sm:text-lg mb-4">Search for an existing user here</h2>
        <form method="GET" action="{{ $submitLink }}">
            <div class="flex gap-2 mb-2">
                <x-input-field name="query" placeholder="Search users..." value="{{ old('query') ?? request('query') }}" class="w-full" />

                <x-link-button link="{{ $submitLink }}" role="destroy">
                    <span class="max-sm:hidden">Clear</span>
                </x-link-button>

                <x-button role="search">
                    <span class="max-sm:hidden">Search</span>
                </x-button>
            </div>
            <p class="text-slate-500 text-justify">
                You can search here by name, email address and telephone number to find your customer.
            </p>
        </form>        
    </x-card>

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
                        <x-link-button link="{{ $view == 'admin' ? route('bookings.create.barber.service',['user_id' => $user->id]) : route('appointments.create.service',['user_id' => $user->id]) }}" role="ctaMain" :maxHeightFit="true">Select customer</x-link-button>
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