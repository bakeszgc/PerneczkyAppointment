<x-user-layout currentView="admin" title="New Barber - ">
    <x-breadcrumbs :links="[
        'Admin Dashboard' => route('admin'),
        'Manage Barbers' => route('barbers.index'),
        'New Barber' => ''
    ]"/>

    <x-headline class="mb-4">Create a New Barber</x-headline>

    <x-card class="mb-8">
        <h2 class="font-bold text-2xl max-sm:text-lg mb-4">Search for an existing user here</h2>
        <form method="GET" action="{{ route('barbers.create') }}">
            <div class="flex gap-2 mb-2">
                <x-input-field name="query" placeholder="Search users..." value="{{ request('query') }}" class="w-full"></x-input-field>

                <x-link-button link="{{ route('barbers.create') }}" role="destroy">Clear</x-link-button>
                <x-button role="search">Search</x-button>
            </div>
            <p class="text-slate-500">
                You can search here by name, email address and telephone number to find your customer.
            </p>
            <p class="text-slate-500">
                After finding the right user, click the 'Promote to Barber' button to add them as a new barber
            </p>
        </form>        
    </x-card>

    <h2 class="font-bold text-2xl mb-4">Search results</h2>

    <x-card class="mb-4">
        <ul class="flex flex-col gap-4">
            @forelse ($users as $user)
                <form action="{{ route('barbers.store') }}" method="POST">
                    @csrf
                    
                    <li class="flex justify-between {{ !$loop->last ? 'border-b pb-2' : '' }}">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                        <div>
                            <h3 class="font-bold text-xl mb-1">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </h3>
                            <p class="text-slate-500">Email: {{ $user->email }}</p>
                            <p class="text-slate-500">Tel: {{ $user->tel_number }}</p>
                        </div>

                        @if ($user->barber)
                            <div class="flex flex-col gap-1 items-end">
                                <x-button role="ctaMain" disabled>Already a Barber</x-button>

                                <a href="{{ route('barbers.show',$user->barber) }}" class="text-sm text-[#0018d5] hover:underline">
                                    View {{ $user->barber->getName() }}'s profile
                                </a>
                            </div>
                        @else
                            <x-button role="ctaMain">Promote to Barber</x-button>
                        @endif
                    </li>
                </form>
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