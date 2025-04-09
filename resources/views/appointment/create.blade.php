<x-user-layout title="Create New Booking - " currentView="barber">
    <x-breadcrumbs :links="[
        'Bookings' => route('appointments.index'),
        'New Booking' => ''
    ]"/>
    <x-headline class="mb-4">
        Create New Booking
    </x-headline>
    
    <x-card class="mb-8">
        <h2 class="font-bold text-2xl max-sm:text-lg mb-4">Search for an existing user here</h2>
        <form method="GET" action="{{ route('appointments.create') }}">
            <div class="flex gap-2 mb-2">
                <x-input-field name="query" placeholder="Search users..." value="{{ request('query') }}" class="w-full"></x-input-field>

                <x-link-button link="{{ route('appointments.create') }}" role="destroy">Clear</x-link-button>
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
                            {{ $user->first_name }} {{ $user->last_name }} #{{ $user->id }}
                        </h3>
                        <p class="text-slate-500">Email: {{ $user->email }}</p>
                        <p class="text-slate-500">Tel: {{ $user->tel_number }}</p>
                    </div>
                    <x-link-button link="{{ route('appointments.create.service',['user_id' => $user->id]) }}" role="loginMain">Select Customer</x-link-button>
                </li>
            @empty
                <li>
                    There are no customers with matching properties.
                </li>
            @endforelse
        </ul>
    </x-card>

    <div @class(['mb-4' => $users->count() == 10])>
        {{ $users->links() }}
    </div>

</x-user-layout>