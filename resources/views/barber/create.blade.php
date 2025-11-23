<x-user-layout currentView="admin" title="{{ __('admin.new_barber') }}">
    <x-breadcrumbs :links="[
        __('home.admin_dashboard') => route('admin'),
        __('admin.barbers') => route('barbers.index'),
        __('admin.new_barber') => ''
    ]"/>

    <x-headline class="mb-4">{{ __('admin.add_a_new_barber') }}</x-headline>

    <x-card class="mb-8">
        <h2 class="font-bold text-2xl max-sm:text-lg mb-4">
            {{ __('admin.search_for_user') }}
        </h2>
        <form method="GET" action="{{ route('barbers.create') }}">
            <div class="flex gap-2 mb-4">
                <x-input-field name="query" placeholder="{{ __('barber.search_users') }}" value="{{ request('query') }}" class="w-full"></x-input-field>

                <x-link-button link="{{ route('barbers.create') }}" role="destroy">
                    <span class="max-sm:hidden">{{ __('barber.clear') }}</span>
                </x-link-button>

                <x-button role="search">
                    <span class="max-sm:hidden">{{ __('barber.search') }}</span>
                </x-button>
            </div>
            <p class="text-slate-500 text-justify mb-2">
                {{ __('barber.registered_p') }}
            </p>
            <p class="text-slate-500 text-justify">
                {{ __('admin.click_to_add') }}
            </p>
        </form>        
    </x-card>

    <h2 class="font-bold text-2xl max-md:text-xl mb-4">
        {{ __('barber.search_results') }}
    </h2>

    <x-card class="mb-4">
        <ul class="flex flex-col gap-4">
            @forelse ($users as $user)
                <form action="{{ route('barbers.store') }}" method="POST">
                    @csrf
                    
                    <li class="flex max-sm:flex-col gap-2 justify-between {{ !$loop->last ? 'border-b pb-2' : '' }}">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">

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

                        @if ($user->barber)
                            <div class="flex flex-col gap-1 items-end max-sm:items-start">
                                <x-button role="ctaMain" disabled :maxHeightFit="true">
                                    {{ __('admin.already_a_barber') }}
                                </x-button>

                                <a href="{{ route('barbers.show',$user->barber) }}" class="text-sm text-[#0018d5] hover:underline">
                                    {{ __('admin.view_s1s_barber_page_1') . $user->barber->getName() . __('admin.view_s1s_barber_page_2') }}
                                </a>
                            </div>
                        @else
                            <x-button role="ctaMain" :maxHeightFit="true">
                                {{ __('admin.promote_to_barber') }}
                            </x-button>
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
        {{ $users->appends($_GET)->links() }}
    </div>

</x-user-layout>