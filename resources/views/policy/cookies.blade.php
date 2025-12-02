@use('Whitecube\LaravelCookieConsent\Facades\Cookies')
<x-user-layout title="{{ __('policy.cookies') }}" currentView="user">

    <x-breadcrumbs :links="[__('policy.cookies') => '']" />
    <x-headline class="mb-4">
        {{ __('home.cookie_policy') }}
    </x-headline>

    <x-card class="mb-8 text-justify">
        <p class="text-xl max-md:text-base mb-4">
            {{ __('policy.cp_p_1') }}
        </p>
        <p class="text-base max-md:text-sm mb-4">
            {{ __('policy.cp_p_2') }}
        </p>

        <ul class="list-disc pl-6 *:mb-2 *:last:mb-0 text-base max-md:text-sm mb-4">
            <li>
                {{ __('policy.cp_p_3') }}
                <a class="font-bold hover:text-blue-700 transition-all" href="#essentials">{{ __('policy.cp_p_3a') }}</a>
                {{ __('policy.cp_p_3b') }}
            </li>
            <li>
                {{ __('policy.cp_p_3') }}
                <a class="font-bold hover:text-blue-700 transition-all" href="#analytics">{{ __('policy.cp_p_4a') }}</a>
                {{ __('policy.cp_p_4b') }}
            </li>
            <li>
                {{ __('policy.cp_p_3') }}
                <a class="font-bold hover:text-blue-700 transition-all" href="#optionals">{{ __('policy.cp_p_5a') }}</a>
                {{ __('policy.cp_p_5b') }}
            </li>
        </ul>

        <p class="text-base max-md:text-sm mb-0">
            {{ __('policy.cp_p_6a') }}

            <a class="font-bold hover:text-blue-700 transition-all" href="#manage-cookies">{{ __('policy.cp_manage_cookies') }}</a>
            
            {{ __('policy.cp_p_6b') }}
        </p>
    </x-card>

    @foreach(Cookies::getCategories() as $category)
        <div id="{{ strtr(strtolower($category->title),array(' ' => '-')) }}" class="-translate-y-20"></div>
        <h2 class="text-2xl max-md:text-xl font-bold mb-2">{{ __('policy.'.$category->title) }}</h2>
        <x-card class="mb-8">
            @foreach($category->getCookies() as $cookie)
                <div class="flex gap-2 border-b-2 pb-4 mb-4 last:pb-0 last:mb-0 last:border-0">
                    <div class="text-lg max-md:text-base max-sm:text-sm">🍪</div>
                    <div class="flex-1">
                        <h3 class="text-lg max-md:text-base max-sm:text-sm font-medium mb-2 break-all">{{ $cookie->name }}</h3>
                        
                        <p class="mb-2">
                            {{ __('policy.'.$cookie->description) }}
                        </p>
                        <p class="mb-4 last:mb-0">
                            {{ __('admin.duration') . ': ' . \Carbon\CarbonInterval::minutes($cookie->duration)->cascade() }}
                        </p>
                    </div>
                </div>   
            @endforeach
        </x-card>
    @endforeach

    <div id="manage-cookies" class="-translate-y-20"></div>
    <div class="mb-4">
        @cookieconsentbutton(action: 'reset', label: __('policy.cp_manage_cookies'), attributes: ['id' => 'reset-button', 'class' => 'btn'])
    </div>

</x-user-layout>