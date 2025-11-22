@php
    $view ??= 'booking';

    switch ($view) {
        case 'timeoff':
            $createRoute = route('admin-time-offs.create');
            $breadcrumbLinks = [
                __('home.admin_dashboard') => route('admin'),
                __('home.time_offs') => route('admin-time-offs.index')
            ];
        break;

        case 'booking':
            $createRoute = route('bookings.create');
            $breadcrumbLinks = [
                __('home.admin_dashboard') => route('admin'),
                __('home.bookings') => route('bookings.index')
            ];
        break;
    }
@endphp

<x-user-layout title="{{ __('admin.all_'.$view.'s') }}" currentView="admin">
    
    <div class="flex justify-between items-end align-bottom mb-4">
        <div>
            <x-breadcrumbs :links="$breadcrumbLinks"/>
            <x-headline>
                {{ __('admin.all_'.$view.'s') }}
            </x-headline>
        </div>
        <div>
            <x-link-button :link="$createRoute" role="{{ $view == 'timeoff' ? 'timeoffCreateMain' : 'createMain' }}">
                <span class="max-sm:hidden">
                    {{ __('admin.new_'.$view) }}
                </span>
            </x-link-button>
        </div>
    </div>

    <x-card class="mb-8">
        <form action="" method="GET" id="filterForm">
            <div @class([
                'grid grid-cols-2 grid-flow-col max-md:grid-cols-1 gap-4 mb-4',
                'grid-rows-3 max-md:grid-rows-6' => $view == 'booking',
                'grid-rows-2 max-md:grid-rows-4' => $view == 'timeoff'
            ])>
                <div class="flex flex-col">
                    <x-label for="barberSelect">{{ __('appointments.barber') }}</x-label>
                    <x-select name="barber" id="barberSelect">
                        <option value="empty">{{ __('admin.select_a_barber') }}</option>
                        @foreach ($barbers as $barber)
                            <option value="{{ $barber->id }}" @selected(request('barber') == $barber->id)>
                                {{ $barber->getName() }} {{ $barber->deleted_at ? '(deleted)' : '' }}
                            </option>
                        @endforeach
                    </x-select>
                    @error('barber')
                        {{ $message }}
                    @enderror
                </div>

                @if ($view == 'booking')
                    <div class="flex flex-col">
                        <x-label for="serviceSelect">{{ __('appointments.service') }}</x-label>
                        <x-select name="service" id="serviceSelect">
                            <option value="empty">{{ __('admin.select_a_service') }}</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" @selected(request('service') == $service->id)>
                                    {{ $service->getName() . ' ' . $service->isDeleted() }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>

                    <div class="flex flex-col">
                        <x-label for="userSelect">{{ __('barber.customer') }}</x-label>
                        <x-select name="user" id="userSelect">
                            <option value="empty">{{ __('admin.select_a_customer') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected(request('user') == $user->id)>
                                    {{ $user->first_name . " " . $user->last_name . " " . $user->isDeleted() }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>
                @endif

                <div class="flex flex-col">
                    @php
                        $timeWindowOptions = [
                            [
                                'id' => 'custom_time_window',
                                'name' => 'custom'
                            ],
                            [
                                'id' => 'previous_time_window',
                                'name' => 'previous'
                            ],
                            [
                                'id' => 'upcoming_time_window',
                                'name' => 'upcoming'
                            ],
                        ];
                    @endphp

                    <x-label for="custom_time_window">{{ __('admin.time_window') }}</x-label>
                    <div class="grid grid-cols-3 gap-2 p-1.5 rounded-md bg-slate-300 text-center text-base max-sm:text-sm font-bold">
                        @foreach ($timeWindowOptions as $timeWindowOption)

                            <label for="{{ $timeWindowOption['id'] }}" class="rounded-md py-1 has-[input:checked]:bg-white transition-all hover:bg-white cursor-pointer">
                                {{ __('admin.'.$timeWindowOption['name']) }}

                                <input type="radio" name="time_window" id="{{ $timeWindowOption['id'] }}" class="hidden" value="{{ $timeWindowOption['name'] }}" @checked(request('time_window') == $timeWindowOption['name'] || $loop->index == 0)>
                            </label>

                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col">
                    <x-label for="fromDate">{{ __('admin.from') }}</x-label>
                    <div class="flex gap-2">
                        <x-input-field type="date" name="from_app_start_date" id="fromDate" class="flex-1 w-full dateTimeInput" value="{{ request('from_app_start_date') }}" :disabled="request('time_window') == 'previous' || request('time_window') == 'upcoming'" />
                        
                        <x-select name="from_app_start_hour" id="fromHour" :disabled="request('time_window') == 'previous' || request('time_window') == 'upcoming'" class="dateTimeInput">
                            <option value="empty"></option>
                            @for ($i=10;$i<=20;$i++)
                                <option value="{{ $i }}" @selected(request('from_app_start_hour') == $i)>
                                    {{ $i }}
                                </option>
                            @endfor
                        </x-select>

                        <x-select name="from_app_start_minute" id="fromMinute" :disabled="request('time_window') == 'previous' || request('time_window') == 'upcoming'" class="dateTimeInput">
                            <option value="empty"></option>
                            @for ($i=0;$i<=45;$i+=15)
                                <option value="{{ $i }}" @selected(request('from_app_start_minute') === strval($i))>
                                    {{ $i === 0 ? '00' : $i }}
                                </option>
                            @endfor
                        </x-select>
                    </div>
                </div>

                <div class="flex flex-col">
                    <x-label for="toDate">{{ __('admin.to') }}</x-label>
                    <div class="flex gap-2">
                        <x-input-field type="date" name="to_app_start_date" id="toDate" :disabled="request('time_window') == 'previous' || request('time_window') == 'upcoming'" class="flex-1 dateTimeInput" value="{{ request('to_app_start_date') }}" />

                        <x-select name="to_app_start_hour" id="toHour" :disabled="request('time_window') == 'previous' || request('time_window') == 'upcoming'" class="dateTimeInput">
                            <option value="empty"></option>
                            @for ($i=10;$i<=20;$i++)
                                <option value="{{ $i }}" @selected(request('to_app_start_hour') == $i)>
                                    {{ $i }}
                                </option>
                            @endfor
                        </x-select>

                        <x-select name="to_app_start_minute" id="toMinute" :disabled="request('time_window') == 'previous' || request('time_window') == 'upcoming'" class="dateTimeInput">
                            <option value="empty"></option>
                            @for ($i=0;$i<=45;$i+=15)
                                <option value="{{$i}}" @selected(request('to_app_start_minute') === strval($i))>
                                    {{ $i === 0 ? '00' : $i }}
                                </option>
                            @endfor
                        </x-select>
                    </div>
                </div>                   
            </div>

            @if ($view == 'booking')
                <div class="*:mt-2 *:flex *:gap-2 *:items-center mb-4">
                    @php
                        $cancelledRadioButtons = [
                            0 => [
                                'name' => 'cancelled_excluded'
                            ],
                            1 => [
                                'name' => 'cancelled_included'
                            ],
                            2 => [
                                'name' => 'cancelled_only'
                            ]
                        ];
                    @endphp

                    @foreach ($cancelledRadioButtons as $cancelledRadioButton => $details)
                        <label for="cancelled_{{ $cancelledRadioButton }}">
                            <x-input-field type="radio" name="cancelled" :value="$cancelledRadioButton" id="cancelled_{{ $cancelledRadioButton }}" :checked="$cancelledRadioButton == (request('cancelled') ?? 1)" />
                            <p>{{ __('admin.'.$details['name']) }}</p>
                        </label>
                    @endforeach
                </div>
            @endif

            <div>
                <x-button role="ctaMain" :full="true" id="submitButton" :disabled="true">{{ __('barber.search') }}</x-button>
            </div>
        </form>
    </x-card>

    <h2 class="text-2xl font-bold mb-4">
        {{ __('barber.search_results') }}
    </h2>

    @forelse ($appointments as $appointment)
        @if ($view == 'timeoff')
            <x-time-off-card access="admin" :appointment="$appointment" :showDetails="true" class="mb-4" />
        @else
            <x-appointment-card access="admin" :appointment="$appointment" :showDetails="true" class="mb-4" />
        @endif        
    @empty
        <x-empty-card>
            <p class="text-lg max-md:text-base font-medium">
                {{ __('admin.no_'.$view.'s_filter') }}
            </p>
            <a href="{{ route('bookings.create') }}" @class(['hover:underline', 'text-blue-700' => $view == 'booking', 'text-green-700' => $view == 'timeoff'])>
                {{ __('admin.new_one') }}
            </a>
        </x-empty-card>
    @endforelse

    <div class="mb-4">
        {{$appointments->appends($_GET)->links()}}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('filterForm');

            form.addEventListener('submit', function (e) {
                const elements = form.querySelectorAll('input, select');
                elements.forEach(el => {
                    if (
                        (el.tagName === 'INPUT' && (el.type === 'text' || el.type === 'date' || el.type === 'radio')) ||
                        el.tagName === 'SELECT'
                    ) {
                        if (!el.value || el.value == '' || el.value == 'empty' || (el.type === 'radio' && !el.checked)) {
                            el.disabled = true;
                        }
                    }
                });
            });

            // DISABLES DATE INPUTS AND HOUR/MINUTE SELECTS WHEN CUSTOM TIME WINDOW ISN'T SELECTED
            const timeWindowSelector = document.querySelectorAll("input[name='time_window']");
            const dateTimeInputs = document.querySelectorAll(".dateTimeInput");

            const fromDateInput = document.getElementById("fromDate");
            const fromHourInput = document.getElementById("fromHour");
            const fromMinuteInput = document.getElementById("fromMinute");

            const toDateInput = document.getElementById("toDate");
            const toHourInput = document.getElementById("toHour");
            const toMinuteInput = document.getElementById("toMinute");

            timeWindowSelector.forEach(timeWindowButton => {
                timeWindowButton.addEventListener('change', function () {
                    checkSelectedTimeWindow(timeWindowButton,dateTimeInputs);
                });
            });

            function checkSelectedTimeWindow(timeWindowButton,dateTimeInput) {
                if (timeWindowButton.value != 'custom') {
                    dateTimeInputs.forEach(input => {
                        input.setAttribute('disabled','');
                    });
                } else {
                    dateTimeInputs.forEach(input => {
                        input.removeAttribute('disabled','');
                    });

                    checkDateInputs(fromDateInput,fromHourInput,fromMinuteInput);
                    checkDateInputs(toDateInput,toHourInput,toMinuteInput);
                }
            }
            
            // ENABLES HOUR AND MINUTE SELECT WHEN DATE AND HOUR HAS VALUE
            checkDateInputs(fromDateInput,fromHourInput,fromMinuteInput);
            checkDateInputs(toDateInput,toHourInput,toMinuteInput);

            fromDateInput.addEventListener('change', function () {
                checkDateInputs(fromDateInput,fromHourInput,fromMinuteInput);
            });

            fromHourInput.addEventListener('change', function () {
                checkDateInputs(fromDateInput,fromHourInput,fromMinuteInput);
            });

            toDateInput.addEventListener('change', function () {
                checkDateInputs(toDateInput,toHourInput,toMinuteInput);
            });

            toHourInput.addEventListener('change', function () {
                checkDateInputs(toDateInput,toHourInput,toMinuteInput);
            });

            function checkDateInputs(dateInput, hourInput, minuteInput) {
                if (dateInput.value == '') {
                    hourInput.disabled = true;
                    minuteInput.disabled = true;
                    hourInput.value = 'empty';
                    minuteInput.value = 'empty';
                } else {
                    hourInput.disabled = false;
                }

                if (hourInput.value == 'empty') {
                    minuteInput.disabled = true;
                    minuteInput.value = 'empty';
                } else {
                    minuteInput.disabled = false;
                }
            }            
        });
    </script>

</x-user-layout>