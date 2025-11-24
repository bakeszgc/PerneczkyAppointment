<x-user-layout :title="__('policy.terms_and_conditions')">

    <x-breadcrumbs :links="[__('home.terms_and_conditions') => '']" />
    
    <div class="flex justify-between items-end mb-4">
        <x-headline>
            {{ __('policy.terms_and_conditions') }}
        </x-headline>
        <p class="text-right">
            {{ __('policy.last_updated') }}: 2025-11-07
        </p>
    </div>

    <x-card class="mb-4">
        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.tc_1_title') }}
            </h2>

            <p class="text-justify mb-4">
                {{ __('policy.tc_1_p_1a') }}
                
                <a href="{{ env('APP_URL') }}" class="text-blue-700 hover:underline">{{ env('APP_URL') }}</a>
                
                {{ __('policy.tc_1_p_1b') }}
            </p>

            <p class="text-justify mb-4">
                {{ __('policy.tc_1_p_2') }}
            </p>

            <p class="text-justify mb-4">
                {{ __('policy.tc_1_p_3') }}
            </p>

            <p class="text-justify mb-4">
                {{ __('policy.tc_1_p_4') }}
            </p>

            <p class="text-justify mb-4">
                {{ __('policy.tc_1_p_5') }}
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.tc_2_title') }}
            </h2>

            <ul class="list-disc *:ml-6 *:mb-2">
                <li>
                    <span class="font-bold">{{ __('policy.tc_2_p_1a') }}</span>
                    {{ env('COMPANY_NAME') }}
                </li>
                <li>
                    <span class="font-bold">{{ __('policy.tc_2_p_2a') }}</span>
                    {{ __('policy.tc_2_p_2b') }}
                </li>
                <li>
                    <span class="font-bold">{{ __('policy.tc_2_p_3a') }}</span>
                    {{ __('policy.tc_2_p_3b') }}
                </li>
                <li>
                    <span class="font-bold">{{ __('policy.tc_2_p_4a') }}</span>
                    <a href="{{ env('APP_URL') }}" class="text-blue-700 hover:underline">{{ env('APP_URL') }}</a>
                    {{ __('policy.tc_2_p_4b') }}
                </li>
            </ul>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.tc_3_title') }}
            </h2>

            <p class="text-justify mb-4">
                {{ __('policy.tc_3_p_1') }}
            </p>

            <ul class="list-disc *:ml-6 *:mb-2">
                <li>
                    <span class="font-bold">{{ __('policy.tc_3_p_2a') }}</span>
                    {{ __('policy.tc_3_p_2b') }}
                </li>
                <li>
                    <span class="font-bold">{{ __('policy.tc_3_p_3a') }}</span>
                    {{ __('policy.tc_3_p_3b') }}
                </li>
                <li>
                    <span class="font-bold">{{ __('policy.tc_3_p_4a') }}</span>
                    {{ __('policy.tc_3_p_4b') }}
                </li>
                <li>
                    <span class="font-bold">{{ __('policy.tc_3_p_5a') }}</span>
                    {{ __('policy.tc_3_p_5b') }}
                </li>
                <li>
                    <span class="font-bold">{{ __('policy.tc_3_p_6a') }}</span>
                    {{ __('policy.tc_3_p_6b') }}
                </li>
            </ul>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.tc_4_title') }}
            </h2>
            <p class="text-justify">
                {{ __('policy.tc_4_p_1') }}
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.tc_5_title') }}
            </h2>
            <p class="text-justify mb-4">
                {{ __('policy.tc_5_p_1') }}
            </p>
            <h3 class="font-bold mb-2">
                {{ __('policy.tc_5_p_2') }}
            </h3>
            <ul class="list-disc *:ml-6 *:mb-2 mb-4">
                <li>
                    {{ __('policy.tc_5_p_3') }}
                </li>
                <li>
                    {{ __('policy.tc_5_p_4') }}
                </li>
                <li>
                    {{ __('policy.tc_5_p_5') }}
                </li>
                <li>
                    {{ __('policy.tc_5_p_6') }}
                </li>
                <li>
                    {{ __('policy.tc_5_p_7') }}
                </li>
            </ul>

            <h3 class="font-bold mb-2">
                {{ __('policy.tc_5_p_8') }}
            </h3>
            <ul class="list-disc *:ml-6 *:mb-2 mb-4">
                <li>
                    {{ __('policy.tc_5_p_9') }}
                </li>
                <li>
                    {{ __('policy.tc_5_p_10') }}
                </li>
                <li>
                    {{ __('policy.tc_5_p_11') }}
                </li>
                <li>
                    {{ __('policy.tc_5_p_12') }}
                </li>
            </ul>

            <h3 class="font-bold mb-2">
                {{ __('policy.tc_5_p_13') }}
            </h3>
            <p class="text-justify mb-4">
                {{ __('policy.tc_5_p_14a') }}<a href="tel:{{ str_replace(' ','',env('COMPANY_PHONE')) }}" class="text-blue-700 hover:underline">{{ env('COMPANY_PHONE') }}</a>{{ __('policy.tc_5_p_14b') }}
            </p>

            <h3 class="font-bold mb-2">
                {{ __('policy.tc_5_p_15') }}
            </h3>
            <p class="text-justify">
                {{ __('policy.tc_5_p_16a') }}<a href="tel:{{ str_replace(' ','',env('COMPANY_PHONE')) }}" class="text-blue-700 hover:underline">{{ env('COMPANY_PHONE') }}</a>{{ __('policy.tc_5_p_16b') }}
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.tc_6_title') }}
            </h2>
            <p class="text-justify">
                {{ __('policy.tc_6_p_1') }}
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.tc_7_title') }}
            </h2>
            <p class="text-justify">
                {{ __('policy.tc_7_p_1') }}
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.tc_8_title') }}
            </h2>
            <p class="text-justify mb-2">
                {{ __('policy.tc_8_p_1') }}
            </p>
            <ul class="list-disc *:ml-6 *:mb-2 mb-2">
                <li>
                    {{ __('policy.tc_8_p_2') }}
                </li>
                <li>
                    {{ __('policy.tc_8_p_3') }}
                </li>
            </ul>
            <p class="text-justify">
                {{ __('policy.tc_8_p_4a') }}
                <a href="{{ route('privacy') }}" class="text-blue-700 hover:underline">{{ __('policy.tc_8_p_4b') }}</a>.
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.tc_9_title') }}
            </h2>
            <p class="text-justify">
                {{ __('policy.tc_9_p_1') }}
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.tc_10_title') }}
            </h2>
            <p class="text-justify mb-2">
                {{ __('policy.tc_10_p_1') }}
            </p>
            <ul class="list-disc *:ml-6 *:mb-2">
                <li>
                    {{ __('policy.tc_10_p_2') }}
                </li>
                <li>
                    {{ __('policy.tc_10_p_3') }}
                </li>
                <li>
                    {{ __('policy.tc_10_p_4') }}
                </li>
                <li>
                    {{ __('policy.tc_10_p_5') }}
                </li>
            </ul>
        </div>

        <div>
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.tc_11_title') }}
            </h2>
            <p class="text-justify mb-4">
                {{ __('policy.tc_11_p_1') }}
            </p>

            <h3 class="font-bold mb-2">
                {{ __('policy.tc_11_p_2') }}
            </h3>
            <ul class="list-disc *:ml-6 *:mb-2 mb-4">
                <li>
                    1364 Budapest, Pf. 144
                </li>
                <li>
                    Email: <a href="mailto:fogyved_kmf_budapest@nfh.hu" class="text-blue-700 hover:underline">fogyved_kmf_budapest@nfh.hu</a>
                </li>
            </ul>

            <h3 class="font-bold mb-2">
                {{ __('policy.tc_11_p_3') }}
            </h3>
            <ul class="list-disc *:ml-6 *:mb-2">
                <li>
                    1525 Budapest, Pf. 75
                </li>
                <li>
                    Email: <a href="mailto:info@nmhh.hu" class="text-blue-700 hover:underline">info@nmhh.hu</a>
                </li>
            </ul>
        </div>

    </x-card>
</x-user-layout>