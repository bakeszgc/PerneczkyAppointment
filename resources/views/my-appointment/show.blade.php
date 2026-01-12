<x-user-layout title="{{ __('appointments.appointment') . ' #' . $appointment->id }}">

    <div class="flex justify-between items-end align-bottom mb-4">
        <div>
            <x-breadcrumbs :links="[
                __('home.my_appointments') => $appointment->app_start_time <= now() ? route('my-appointments.index.previous') : route('my-appointments.index'),
                __('appointments.appointment') . ' #' . $appointment->id  => ''
            ]"/>
            <x-headline>
                {{ $appointment->app_start_time <= now() ? __('appointments.my_previous_appointment') : __('appointments.my_upcoming_appointment')}}
            </x-headline>
        </div>
        <div>
            <x-link-button :link="route('my-appointments.create')" role="createMain">
                <span class="max-sm:hidden">
                    {{ __('appointments.new_appointment') }}
                </span>
            </x-link-button>
        </div>
    </div>

    <x-appointment-card :appointment="$appointment" access="user" class="mb-4">
        <div class="text-base max-md:text-sm text-slate-500 mt-1">
            {{ __('appointments.comment') }}:
            @if (!$appointment->comment)
                <span class="italic">
                    {{ __('appointments.no_comment') }}
                </span>
            @else
                {{ $appointment->comment }}
            @endif
        </div>
    </x-appointment-card>

    @if ($appointment->app_start_time >= now()->addHours(6))
        <div class="mb-4 grid grid-cols-2 max-sm:grid-cols-1 gap-4">
            <x-card>
                <h2 class="text-lg font-bold mb-4 flex gap-2 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                    </svg>

                    <span>{{ __('appointments.edit_title') }}</span>
                </h2>

                <p class="text-justify mb-4">
                    {{ __('appointments.edit_p1a') }}
                    ✏️<span class="max-sm:hidden font-bold">&nbsp;{{ __('appointments.edit') }}</span>
                    {{ __('appointments.edit_p1b') }}
                </p>

                <p class="text-justify mb-4">
                    {{ __('appointments.edit_p2') }}
                </p>

                <p class="text-justify">
                    {{ __('appointments.edit_p3a') }}
                    <a href="mailto:{{ env('COMPANY_MAIL') }}" class="text-blue-700 hover:underline">{{ env('COMPANY_MAIL') }}</a>
                    {{ __('appointments.edit_p3b') }}
                    <a href="tel:{{ str_replace(' ','',env('COMPANY_PHONE')) }}" class="text-blue-700 hover:underline">{{ env('COMPANY_PHONE') }}</a>{{ __('appointments.edit_p3c') }}
                </p>
            </x-card>

            <x-card>
                <h2 class="text-lg font-bold mb-4 flex gap-2 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>

                    <span>{{ __('appointments.cancel_title') }}</span>
                </h2>

                <p class="text-justify mb-4">
                    {{ __('appointments.cancel_p1a') }}
                    🗑️<span class="max-sm:hidden font-bold">&nbsp;{{ __('appointments.cancel') }}</span>
                    {{ __('appointments.cancel_p1b') }}
                </p>

                <p class="text-justify mb-4">
                    {{ __('appointments.cancel_p2a') }}
                    ➕<span class="max-sm:hidden font-bold">&nbsp;{{ __('appointments.new_appointment') }}</span>
                    {{ __('appointments.cancel_p2b') }}
                </p>

                <p class="text-justify">
                    {{ __('appointments.edit_p3a') }}
                    <a href="mailto:{{ env('COMPANY_MAIL') }}" class="text-blue-700 hover:underline">{{ env('COMPANY_MAIL') }}</a>
                    {{ __('appointments.edit_p3b') }}
                    <a href="tel:{{ str_replace(' ','',env('COMPANY_PHONE')) }}" class="text-blue-700 hover:underline">{{ env('COMPANY_PHONE') }}</a>{{ __('appointments.edit_p3c') }}
                </p>
            </x-card>
        </div>
    @elseif ($appointment->app_start_time < now())
        <x-card class="mb-4">
            <h2 class="text-lg font-bold mb-4 flex gap-2 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>{{ __('appointments.rebook_title') }}</span>
            </h2>

            <p class="text-justify mb-4">
                {{ __('appointments.rebook_p1a') }}
                🔁<span class="max-sm:hidden font-bold">&nbsp;{{ __('appointments.rebook') }}</span>
                {{ __('appointments.rebook_p1b') }}
            </p>

            <p class="text-justify">
                {{ __('appointments.edit_p3a') }}
                <a href="mailto:{{ env('COMPANY_MAIL') }}" class="text-blue-700 hover:underline">{{ env('COMPANY_MAIL') }}</a>
                {{ __('appointments.edit_p3b') }}
                <a href="tel:{{ str_replace(' ','',env('COMPANY_PHONE')) }}" class="text-blue-700 hover:underline">{{ env('COMPANY_PHONE') }}</a>{{ __('appointments.edit_p3c') }}
            </p>
        </x-card>
    @else
        <x-card class="mb-4">
            <h2 class="text-lg font-bold mb-4">
                {{ $appointment->getTimeDiff() }}
            </h2>

            <p class="mb-4 text-justify">
                {{ __('appointments.upcoming_p1') }}
            </p>

            <p class="mb-4 text-justify">
                {{ __('appointments.upcoming_p2') }}
                {{ __('appointments.upcoming_p3') }}
            </p>

            <p class="text-justify">
                    {{ __('appointments.edit_p3a') }}
                    <a href="mailto:{{ env('COMPANY_MAIL') }}" class="text-blue-700 hover:underline">{{ env('COMPANY_MAIL') }}</a>
                    {{ __('appointments.edit_p3b') }}
                    <a href="tel:{{ str_replace(' ','',env('COMPANY_PHONE')) }}" class="text-blue-700 hover:underline">{{ env('COMPANY_PHONE') }}</a>{{ __('appointments.edit_p3c') }}
                </p>
        </x-card>
    @endif

    <div class="text-center mb-4">
        <p>
            {{ $appointment->app_start_time >= now('Europe/Budapest') ? __('appointments.fresh_cut_earlier') : __('appointments.fresh_cut') }}
        </p>
        <a href="{{ route('my-appointments.create') }}" class="text-blue-700 hover:underline">
            {{ __('appointments.book_appointment_here') }}
        </a>
    </div>
</x-user-layout>