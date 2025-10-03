<x-user-layout title="Manage customers" currentView="admin">
    <x-breadcrumbs :links="[
            'Admin dashboard' => route('admin'),
            'Customers' => ''
        ]"
    />

    <x-headline class="mb-4">
        Manage customers
    </x-headline>
    
    <x-card class="mb-8">
        <h2 class="font-bold text-2xl max-sm:text-lg mb-4">Search for an existing user here</h2>
        <form method="GET" action="{{ route('customers.index') }}">
            <div class="flex gap-2 mb-2">
                <x-input-field name="query" placeholder="Search users..." value="{{ old('query') ?? request('query') }}" class="w-full" />

                <x-link-button link="{{ route('customers.index') }}" role="destroy">
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

    <x-card class="mb-4">
        <ul class="flex flex-col gap-4">
            @forelse ($users as $userDetails)
                <x-user-card :userDetails="$userDetails" @class(['border-b pb-8' => !$loop->last]) />
            @empty
                <x-empty-card>
                    There aren't any users with matching properties
                </x-empty-card>
            @endforelse
        </ul>
    </x-card>

    <div @class(['mb-4' => $users->count() == 10])>
        {{ $users->appends($_GET)->links() }}
    </div>

</x-user-layout>