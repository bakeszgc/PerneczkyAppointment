@php
    $view = $view ?? 'barber';

    if($view == 'admin') {
        $breadcrumbLinks = [
            'Admin Dashboard' => route('admin'),
            'Bookings' => route('bookings.index'),
            'New Booking' => ''
        ];
        $submitLink = route('bookings.create');
    } else {
        $breadcrumbLinks = [
            'Bookings' => route('appointments.index'),
            'New Booking' => ''
        ];
        $submitLink = route('appointments.create');
    }
@endphp

<x-user-layout title="New Booking - " currentView="{{ $view }}">
    <x-breadcrumbs :links="$breadcrumbLinks"/>
    <x-headline class="mb-4">
        Create New Booking
    </x-headline>
    
    <x-card class="mb-8">
        <h2 class="font-bold text-2xl max-sm:text-lg mb-4">Search for an existing user here</h2>
        <form method="GET" action="{{ $submitLink }}">
            <div class="flex gap-2 mb-2">
                <x-input-field name="query" placeholder="Search users..." value="{{ old('query') ?? request('query') }}" class="w-full" />

                <x-link-button link="{{ $submitLink }}" role="destroy">Clear</x-link-button>
                <x-button role="search">Search</x-button>
            </div>
            <p class="text-slate-500">
                You can search here by name, email address and telephone number to find your customer.
            </p>
        </form>        
    </x-card>

    <h2 class="font-bold text-2xl mb-4">Search results</h2>

    <x-card class="mb-4">
        <ul class="flex flex-col gap-4">
            @forelse ($users as $user)
                <li class="flex justify-between {{ !$loop->last ? 'border-b pb-2' : '' }}">
                    <div>
                        <h3 class="font-bold text-xl mb-1">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </h3>
                        <p class="text-slate-500">Email: {{ $user->email }}</p>
                        <p class="text-slate-500">Tel: {{ $user->tel_number }}</p>
                    </div>
                    <x-link-button link="{{ $view == 'admin' ? route('bookings.create.barber.service',['user_id' => $user->id]) : route('appointments.create.service',['user_id' => $user->id]) }}" role="ctaMain">Select Customer</x-link-button>
                </li>
            @empty
                <x-empty-card>
                    There aren't any users with matching properties
                </x-empty-card>
            @endforelse
        </ul>
    </x-card>

    <div @class(['mb-4' => $users->count() == 10])>
        {{ $users->links() }}
    </div>

</x-user-layout>