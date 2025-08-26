<x-user-layout title="All Bookings" currentView="admin">

    <x-breadcrumbs :links="[
        'Admin Dashboard' => route('admin'),
        'Bookings' => route('bookings.index')
    ]"/>

    <div class="flex justify-between items-end mb-4">
        <x-headline>
            All Bookings
        </x-headline>
        
        <x-link-button :link="route('bookings.create')" role="createMain">New&nbsp;booking</x-link-button>
    </div>

    <x-card class="mb-8">
        <form action="" method="GET" id="filterForm">
            <div class="grid grid-cols-2 gap-4 mb-8">
                <div>
                    <div class="flex flex-col mb-4">
                        <x-label for="barberSelect">Barber</x-label>
                        <x-select name="barber" id="barberSelect">
                            <option value="empty"></option>
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

                    <div class="flex flex-col mb-4">
                        <x-label for="serviceSelect">Service</x-label>
                        <x-select name="service" id="serviceSelect">
                            <option value="empty"></option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" @selected(request('service') == $service->id)>
                                    {{ $service->name }} {{ $service->deleted_at ? '(deleted)' : '' }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>

                    <div class="flex flex-col mb-4">
                        <x-label for="userSelect">Customer</x-label>
                        <x-select name="user" id="userSelect">
                            <option value="empty"></option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected(request('user') == $user->id)>
                                    {{ $user->first_name . " " . $user->last_name }} {{ $user->deleted_at ? '(deleted)' : '' }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>

                    <div class="*:mt-2 *:flex *:gap-2 *:items-center">

                        @php
                            $cancelledRadioButtons = [
                                0 => [
                                    'name' => 'Cancelled excluded'
                                ],
                                1 => [
                                    'name' => 'Cancelled included'
                                ],
                                2 => [
                                    'name' => 'Cancelled only'
                                ]
                            ];
                        @endphp

                        @foreach ($cancelledRadioButtons as $cancelledRadioButton => $details)
                            <label for="cancelled_{{ $cancelledRadioButton }}">
                                <x-input-field type="radio" name="cancelled" :value="$cancelledRadioButton" id="cancelled_{{ $cancelledRadioButton }}" :checked="$cancelledRadioButton == (request('cancelled') ?? 1)" />
                                <p>{{ $details['name'] }}</p>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <div class="flex flex-col mb-4">
                        <x-label for="fromDate">From</x-label>
                        <div class="flex gap-2">
                            <x-input-field type="date" name="from_app_start_date" id="fromDate" class="flex-1 dateTimeInput" value="{{ request('from_app_start_date') }}" :disabled="request('time_window') == 'previous' || request('time_window') == 'upcoming'" />
                            
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

                    <div class="flex flex-col mb-4">
                        <x-label for="toDate">To</x-label>
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

                        <x-label for="custom_time_window">Time window</x-label>
                        <div class="grid grid-cols-3 gap-2 p-2 rounded-md bg-slate-300 text-center text-lg font-bold">
                            @foreach ($timeWindowOptions as $timeWindowOption)

                                <label for="{{ $timeWindowOption['id'] }}" class="rounded-md has-[input:checked]:bg-white transition-all hover:bg-white cursor-pointer">
                                    {{ ucfirst($timeWindowOption['name']) }}

                                    <input type="radio" name="time_window" id="{{ $timeWindowOption['id'] }}" class="hidden" value="{{ $timeWindowOption['name'] }}" @checked(request('time_window') == $timeWindowOption['name'] || $loop->index == 0)>
                                </label>

                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <x-button role="ctaMain" :full="true" id="submitButton" :disabled="true">Search</x-button>
            </div>
        </form>
    </x-card>

    <h2 class="text-2xl font-bold mb-4">
        Search results
    </h2>

    @forelse ($appointments as $appointment)
        <x-appointment-card access="admin" :appointment="$appointment" :showDetails="true" class="mb-4" />
    @empty
        <x-empty-card>
            <p class="text-lg font-medium">No bookings were found for the applied filters!</p>
            <a href="{{ route('bookings.create') }}" class=" text-blue-700 hover:underline">Add a new booking here for one of your clients!</a>
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
                }
            }
            
            // adads
            const submitButton = document.getElementById("submitButton");
            if (submitButton) {
                const allInput = document.querySelectorAll('input, select, textarea');

                allInput.forEach(el => {
                    if (
                        (el.tagName === 'INPUT' && (el.type === 'text' || el.type === 'date' || el.type === 'radio')) ||
                        el.tagName === 'SELECT' || el.tagName === 'TEXTAREA'
                    ) {
                        el.addEventListener('change', function () {
                            submitButton.disabled = false;
                        });
                    }
                    
                });
            }
        });
    </script>

</x-user-layout>