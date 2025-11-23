<x-user-layout title="{{ __('admin.manage_customers') }}" currentView="admin">
    <x-breadcrumbs :links="[
            __('home.admin_dashboard') => route('admin'),
            __('admin.customers') => ''
        ]"
    />

    <x-headline class="mb-4">
        {{ __('admin.manage_customers') }}
    </x-headline>
    
    <x-card class="mb-8">
        <h2 class="font-bold text-2xl max-sm:text-lg mb-4">
            {{ __('admin.search_for_user') }}
        </h2>
        <form method="GET" action="{{ route('customers.index') }}">
            <div class="flex gap-2 mb-2">
                <x-input-field name="query" placeholder="{{ __('barber.search_users') }}" value="{{ old('query') ?? request('query') }}" class="w-full" />

                <x-link-button link="{{ route('customers.index') }}" role="destroy">
                    <span class="max-sm:hidden">{{ __('barber.clear') }}</span>
                </x-link-button>

                <x-button role="search">
                    <span class="max-sm:hidden">{{ __('barber.search') }}</span>
                </x-button>
            </div>
            <p class="text-slate-500 text-justify">
                {{ __('barber.registered_p') }}
            </p>
        </form>        
    </x-card>

    <h2 class="font-bold text-2xl max-md:text-xl mb-4">{{ __('barber.search_results') }}</h2>

    @if ($users->count() > 0)
        <x-card class="mb-4">
            <ul class="flex flex-col gap-4">
                @foreach ($users as $userDetails)
                    <x-user-card :userDetails="$userDetails" @class(['border-b pb-8' => !$loop->last]) />
                @endforeach
            </ul>
        </x-card>
    @else
        <x-empty-card>
            {{ __('admin.no_users') }}
        </x-empty-card>
    @endif
    

    <div @class(['mb-4' => $users->count() == 10])>
        {{ $users->appends($_GET)->links() }}
    </div>

</x-user-layout>