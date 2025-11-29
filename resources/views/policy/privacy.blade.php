<x-user-layout title="{{ __('policy.privacy') }}">

    <x-breadcrumbs :links="[__('policy.privacy') => '']" />
    
    <div class="flex justify-between items-end mb-4">
        <x-headline>{{ __('home.privacy_policy') }}</x-headline>
        <p>{{ __('policy.last_updated') }}: 2025-11-05</p>
    </div>

    <x-card class="mb-4">
        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.pp_1_title') }}
            </h2>
            <p class="text-justify">
                {{ __('policy.pp_1_p') }}
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.pp_2_title') }}
            </h2>
            <ul class="*:mb-2 list-disc *:ml-6 mb-4">
                <li>
                    {{ __('policy.pp_2_p_1') . env('COMPANY_NAME') }}
                </li>
                <li>
                    {{ __('policy.pp_2_p_2') . env('COMPANY_ADDRESS') }}
                </li>
                <li>
                    {{ __('policy.pp_2_p_3a') }}
                    <a href="{{ env('APP_URL') }}" class="text-blue-700 hover:underline">{{ env('APP_URL') }}</a>
                </li>
                <li>
                    {{ __('policy.pp_2_p_4a') }}
                    <a href="mailto:{{ env('COMPANY_MAIL') }}" class="text-blue-700 hover:underline">{{ env('COMPANY_MAIL') }}</a>
                </li>
                <li>
                    {{ __('policy.pp_2_p_5a') }}
                    <a href="tel:{{ str_replace(' ','',env('COMPANY_PHONE')) }}" class="text-blue-700 hover:underline">{{ env('COMPANY_PHONE') }}</a>
                </li>
            </ul>
            <p class="text-justify">
                {{ __('policy.pp_2_p_6') }}
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.pp_3_title') }}
            </h2>
            <p class="mb-4 text-justify">
                {{ __('policy.pp_3_p_1') }}
            </p>
            <div class="overflow-auto">
                <table class="w-full mb-4 overflow-auto">
                    <thead class="text-left *:*:p-2 *:*:border-2 bg-slate-300">
                        <th>
                            {{ __('policy.pp_3_p_2a') }}
                        </th>
                        <th>
                            {{ __('policy.pp_3_p_2b') }}
                        </th>
                    </thead>
                    <tbody class="*:*:p-2 *:*:border-2"">
                        <tr>
                            <td>{{ __('policy.pp_3_p_3a') }}</td>
                            <td>{{ __('policy.pp_3_p_3b') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('policy.pp_3_p_4a') }}</td>
                            <td>{{ __('policy.pp_3_p_4b') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('policy.pp_3_p_5a') }}</td>
                            <td>{{ __('policy.pp_3_p_5b') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('policy.pp_3_p_6a') }}</td>
                            <td>{{ __('policy.pp_3_p_6b') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('policy.pp_3_p_7a') }}</td>
                            <td>{{ __('policy.pp_3_p_7b') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('policy.pp_3_p_8a') }}</td>
                            <td>{{ __('policy.pp_3_p_8b') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('policy.pp_3_p_9a') }}</td>
                            <td>{{ __('policy.pp_3_p_9b') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="text-justify">{{ __('policy.pp_3_p_10') }}</p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.pp_4_title') }}
            </h2>
            <p class="mb-4 text-justify">
                {{ __('policy.pp_4_p_1') }}
            </p>
            <div class="overflow-auto">
                <table class="w-full mb-4">
                    <thead class="text-left *:*:p-2 *:*:border-2 bg-slate-300">
                        <th>
                            {{ __('policy.pp_4_p_2a') }}
                        </th>
                        <th>
                            {{ __('policy.pp_4_p_2b') }}
                        </th>
                        <th>
                            {{ __('policy.pp_4_p_2c') }}
                        </th>
                    </thead>
                    <tbody class="*:*:p-2 *:*:border-2"">
                        <tr>
                            <td>{{ __('policy.pp_4_p_3a') }}</td>
                            <td>{{ __('policy.pp_4_p_3b') }}</td>
                            <td>{{ __('policy.pp_4_p_3c') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('policy.pp_4_p_4a') }}</td>
                            <td>{{ __('policy.pp_4_p_4b') }}</td>
                            <td>{{ __('policy.pp_4_p_4c') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('policy.pp_4_p_5a') }}</td>
                            <td>{{ __('policy.pp_4_p_5b') }}</td>
                            <td>{{ __('policy.pp_4_p_5c') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('policy.pp_4_p_6a') }}</td>
                            <td>{{ __('policy.pp_4_p_6b') }}</td>
                            <td>{{ __('policy.pp_4_p_6c') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('policy.pp_4_p_7a') }}</td>
                            <td>{{ __('policy.pp_4_p_7b') }}</td>
                            <td>{{ __('policy.pp_4_p_7c') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('policy.pp_4_p_8a') }}</td>
                            <td>{{ __('policy.pp_4_p_8b') }}</td>
                            <td>{{ __('policy.pp_4_p_8c') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="text-justify">
                {{ __('policy.pp_4_p_9') }}    
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.pp_5_title') }}
            </h2>
            <p class="mb-4 text-justify">
                {{ __('policy.pp_5_p_1') }}
            </p>
            <ul class="*:mb-2 list-disc *:ml-6 mb-4">
                <li>{{ __('policy.pp_5_p_2') }}</li>
                <li>{{ __('policy.pp_5_p_3') }}</li>
                <li>{{ __('policy.pp_5_p_4') }}</li>
            </ul>
            <p class="text-justify">
                {{ __('policy.pp_5_p_5') }}
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.pp_6_title') }}
            </h2>
            <p class="mb-4 text-justify">
                {{ __('policy.pp_6_p_1') }}
            </p>
            <ul class="*:mb-2 list-disc *:ml-6 mb-4">
                <li>{{ __('policy.pp_6_p_2') }}</li>
                <li>{{ __('policy.pp_6_p_3') }}</li>
            </ul>
            <p class="text-justify">
                {{ __('policy.pp_6_p_4') }}
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.pp_7_title') }}
            </h2>
            <p class="mb-4 text-justify">
                {{ __('policy.pp_7_p_1') }}
            </p>
            <p class="text-justify">
                {{ __('policy.pp_7_p_2a') }}
                <a href="{{ route('cookies') }}" class="text-blue-700 hover:underline">{{ __('policy.pp_7_p_2b') }}</a>.
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.pp_8_title') }}
            </h2>
            <p class="mb-4 text-justify">
                {{ __('policy.pp_8_p_1') }}
            </p>
            <div class="overflow-auto">
                <table class="w-full mb-4">
                    <thead class="text-left *:*:p-2 *:*:border-2 bg-slate-300">
                        <th>
                            {{ __('policy.pp_8_p_2a') }}
                        </th>
                        <th>
                            Description
                        </th>
                    </thead>
                    <tbody class="*:*:p-2 *:*:border-2"">
                        <tr>
                            <td>{{ __('policy.pp_8_p_3a') }}</td>
                            <td>{{ __('policy.pp_8_p_3a') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('policy.pp_8_p_4a') }}</td>
                            <td>{{ __('policy.pp_8_p_4a') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('policy.pp_8_p_5a') }}</td>
                            <td>{{ __('policy.pp_8_p_5a') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('policy.pp_8_p_6a') }} </td>
                            <td>{{ __('policy.pp_8_p_6a') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('policy.pp_8_p_7a') }} </td>
                            <td>{{ __('policy.pp_8_p_7a') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('policy.pp_8_p_8a') }} </td>
                            <td>{{ __('policy.pp_8_p_8a') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('policy.pp_8_p_9a') }}</td>
                            <td>{{ __('policy.pp_8_p_9a') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="mb-4 text-justify">
                {{ __('policy.pp_8_p_10a') }}

                <a href="mailto:{{ env('COMPANY_MAIL') }}" class="text-blue-700 hover:underline">{{ env('COMPANY_MAIL') }}</a>

                {{ __('policy.pp_8_p_11b') }}
            </p>
            <p class="mb-4 text-justify">
                {{ __('policy.pp_8_p_12') }}
            </p>
            <h3 class="font-bold mb-2">Nemzeti Adatvédelmi és Információszabadság Hatóság (NAIH)</h3>
            <ul class="*:mb-2 list-disc *:ml-6">
                <li>
                    {{ __('policy.pp_2_p_3a') }}
                    <a href="https://www.naih.hu" class="text-blue-700 hover:underline">https://www.naih.hu</a>
                </li>
                <li>
                    {{ __('policy.pp_2_p_2') }}1055 Budapest, Falk Miksa utca 9-11.
                </li>
                <li>
                    {{ __('policy.pp_2_p_5a') }}
                    <a href="tel:+3613911400" class="text-blue-700 hover:underline">+36 (1) 391-1400</a>
                </li>
            </ul>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.pp_9_title') }}
            </h2>
            <p class="mb-4 text-justify">
                {{ __('policy.pp_9_p_1') }}
            </p>
            <ul class="*:mb-2 list-disc *:ml-6 mb-4">
                <li>{{ __('policy.pp_9_p_2') }}</li>
                <li>{{ __('policy.pp_9_p_3') }}</li>
                <li>{{ __('policy.pp_9_p_4') }}</li>
            </ul>
            <p class="text-justify">
                {{ __('policy.pp_9_p_5') }}
            </p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.pp_10_title') }}
            </h2>
            <p class="text-justify">
                {{ __('policy.pp_10_p') }}
            </p>
        </div>

        <div>
            <h2 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('policy.pp_11_title') }}
            </h2>
            <p class="text-justify mb-4">
                {{ __('policy.pp_11_p_1') }}
            </p>
            <h3 class="font-bold mb-2">{{ env('APP_NAME') }}</h3>
            <ul class="*:mb-2 list-disc *:ml-6">
                <li>
                    {{ __('policy.pp_2_p_4a') }}
                    <a href="mailto:{{ env('COMPANY_MAIL') }}" class="text-blue-700 hover:underline">{{ env('COMPANY_MAIL') }}</a>
                </li>
                <li>
                    {{ __('policy.pp_2_p_5a') }}
                    <a href="tel:{{ str_replace(' ','',env('COMPANY_PHONE')) }}" class="text-blue-700 hover:underline">{{ env('COMPANY_PHONE') }}</a>
                </li>
                <li>
                    {{ __('policy.pp_2_p_2') . env('COMPANY_ADDRESS') }}
                </li>
            </ul>
        </div>
    </x-card>
</x-user-layout>