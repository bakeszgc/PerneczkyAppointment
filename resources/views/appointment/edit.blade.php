@php
    use Carbon\Carbon;

    $view ??= 'Booking';
    $access ??= 'barber';
    $action ??= 'edit';

    switch ($access) {
        case 'admin':
            switch ($view) {
                case 'Time Off':
                    switch ($action) {
                        case 'edit':
                            $title = __('barber.editing_s1s_1') . $appointment->barber->getName() . __('barber.editing_s1s_timeoff_2');
                            $formRoute = route('admin-time-offs.update',$appointment);
                            $destroyRoute = route('admin-time-offs.destroy',$appointment);
                            $breadcrumbLinks = [
                                __('home.admin_dashboard') => route('admin'),
                                __('home.time_offs') => route('admin-time-offs.index'),
                                __('barber.time_off') . " #" . $appointment->id => route('admin-time-offs.show',$appointment),
                                __('appointments.edit') => ''
                            ];
                        break;

                        case 'create':
                            $title = __('barber.new_time_off');
                            $formRoute = route('admin-time-offs.store');
                            $breadcrumbLinks = [
                                __('home.admin_dashboard') => route('admin'),
                                __('home.time_offs') => route('admin-time-offs.index'),
                                __('barber.new_time_off') => ''
                            ];
                        break;
                    }
                    
                break;

                case 'Booking':
                    $title = __('barber.editing_s1s_1') . $appointment->user->first_name . __('barber.editing_s1s_booking_2');
                    $formRoute = route('bookings.update',$appointment);
                    $destroyRoute = route('bookings.destroy',$appointment);
                    $breadcrumbLinks = [
                        __('home.admin_dashboard') => route('admin'),
                        __('home.bookings') => route('bookings.index'),
                        __('barber.booking') . " #" . $appointment->id => route('bookings.show',$appointment),
                        __('appointments.edit') => ''
                    ];
                break;
            }
        break;

        case 'barber':
            switch ($view) {
                case 'Time Off':
                    switch ($action) {
                        case 'edit':
                            $title = __('barber.editing_your_timeoff');
                            $formRoute = route('time-offs.update',$appointment);
                            $destroyRoute = route('time-offs.destroy',$appointment);
                            $breadcrumbLinks = [
                                __('home.time_offs') => route('time-offs.index'),
                                __('barber.time_off') . " #" . $appointment->id => route('time-offs.show',$appointment),
                                __('appointments.edit') => ''
                            ];
                        break;

                        case 'create':
                            $title = __('barber.new_time_off');
                            $formRoute = route('time-offs.store');
                            $breadcrumbLinks = [
                                __('home.time_offs') => route('time-offs.index'),
                                __('barber.new_time_off') => ''
                            ];
                        break;
                    }
                break;

                case 'Booking':
                    $title = __('barber.editing_s1s_1') . $appointment->user->first_name . __('barber.editing_s1s_booking_2');
                    $formRoute = route('appointments.update',$appointment);
                    $destroyRoute = route('appointments.destroy',$appointment);
                    $breadcrumbLinks = [
                        __('home.bookings') => route('appointments.index'),
                        __('barber.booking') . " #" . $appointment->id => route('appointments.show',$appointment),
                        __('appointments.edit') => ''
                    ];
                break;
            }
        break;
    }
@endphp

<x-user-layout :title="$title" currentView="{{ $access }}">

    <x-breadcrumbs :links="$breadcrumbLinks"/>

    <x-headline class="mb-4">
        {{ $title }}
    </x-headline>

    <x-card class="mb-4">
        @if ($action == 'edit')
            <h1 class="font-bold text-2xl max-sm:text-lg mb-4">
                {{$appointment->user->first_name . " " . $appointment->user->last_name}} #{{$appointment->id}}
            </h1>
        @endif        
        
        <form action="{{ $formRoute }}" method="POST">
            @csrf
            @if ($action == 'edit')
                @method('PUT')
            @endif

            <div class="mb-4 grid grid-cols-2 max-sm:grid-cols-1 gap-4">

                @if ($view == 'Booking')
                    <div class="max-sm:col-span-2">
                        <x-label for="service">
                            {{ __('appointments.service') }}*
                        </x-label>

                        <x-select name="service" id="service" class="w-full">
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" @selected($service->id == $appointment->service_id)>
                                    {{ $service->getName() }}
                                </option>
                            @endforeach
                        </x-select>

                        @error('service')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="max-sm:col-span-2">
                        <x-label for="price">
                            {{ __('barber.price_in_huf') }}*
                        </x-label>

                        <x-input-field type="number" name="price" id="price" :value="$appointment->price" class="w-full h-fit"/>

                        @error('price')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                @endif

                @if ($access == 'admin' && $view == 'Time Off' && $action == 'create')
                    <div class=" col-span-2">
                        <x-label for="barber">{{ __('appointments.barber') }}*</x-label>
                        
                        <x-select name="barber" id="barber" class="w-full">
                            <option value="empty"></option>
                            @foreach ($barbers as $barber)
                                <option value="{{ $barber->id }}" @selected($barber->id == (old('barber') ?? request('barber')))>
                                    {{ $barber->getName() }}
                                </option>
                            @endforeach
                        </x-select>

                        @error('barber')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                @endif

                <div class="flex flex-col max-md:col-span-2">
                    <x-label for="startDate">
                        {{ $view == 'Booking' ? __('barber.bookings_start_time') : __('barber.timeoffs_start_time') }}
                    </x-label>

                    <div class="flex items-center gap-1">
                        <x-input-field type="date" name="app_start_date" id="startDate" value="{{ isset($appointment) ? Carbon::parse($appointment->app_start_time)->format('Y-m-d') : now()->format('Y-m-d') }}" class="flex-1 mr-1 appStartInput" />

                        <x-select name="app_start_hour" id="startHour" :disabled="isset($appointment) && $appointment->isFullDay() && $view == 'Time Off'" class="appStartInput">
                            @for ($i=10;$i<20;$i++)
                                <option value="{{ $i }}" @selected(isset($appointment) ? $i == Carbon::parse($appointment->app_start_time)->format('G') : $i == 10)>{{ $i }}</option>
                            @endfor
                        </x-select>

                        <x-select name="app_start_minute" id="startMinute" :disabled="isset($appointment) && $appointment->isFullDay() && $view == 'Time Off'" class="appStartInput">
                            @for ($i=0;$i<60;$i+=15)
                                <option value="{{ $i }}" @selected(isset($appointment) ? $i == Carbon::parse($appointment->app_start_time)->format('i') : $i == 0)>{{ $i == 0 ? '00' : $i}}</option>
                            @endfor
                        </x-select>
                    </div>                 

                    @error('app_start_date')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                    @error('app_start_hour')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                    @error('app_start_minute')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>

                <div class="flex flex-col max-md:col-span-2">
                    <x-label for="endDate">
                        {{ $view == 'Booking' ? __('barber.bookings_end_time') : __('barber.timeoffs_end_time') }}
                    </x-label>

                    <div class="flex items-center gap-1">
                        <x-input-field type="date" name="app_end_date" id="endDate" value="{{ isset($appointment) ? Carbon::parse($appointment->app_end_time)->format('Y-m-d') : now()->format('Y-m-d') }}" class="flex-1 mr-1 appEndInput" />

                        <x-select name="app_end_hour" id="endHour" :disabled="isset($appointment) && $appointment->isFullDay() && $view == 'Time Off'" class="appEndInput">
                            @for ($i=10;$i<22;$i++)
                                <option value="{{ $i }}" @selected(isset($appointment) ? $i == Carbon::parse($appointment->app_end_time)->format('G') : $i == 11)>{{ $i }}</option>
                            @endfor
                        </x-select>

                        <x-select name="app_end_minute" id="endMinute" :disabled="isset($appointment) && $appointment->isFullDay() && $view == 'Time Off'" class="appEndInput">
                            @for ($i=0;$i<60;$i+=15)
                                <option value="{{ $i }}" @selected(isset($appointment) ? $i == Carbon::parse($appointment->app_end_time)->format('i') : $i == 0)>
                                    {{ $i == 0 ? '00' : $i}}
                                </option>
                            @endfor
                        </x-select>
                    </div>                  
                    
                    @error('app_end_date')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                    @error('app_end_hour')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                    @error('app_end_minute')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>
            </div>

            @switch($view)
                @case('Booking')
                    <div @class(['mb-4','grid grid-cols-2 max-md:grid-cols-1 gap-4  mb-4'])>
                        <div class="flex flex-col">
                            <x-label for="comment">{{ __('appointments.comment') }}</x-label>

                            <x-input-field type="textarea" name="comment" id="comment">{{old('comment') ?? $appointment->comment}}</x-input-field>

                            @error('comment')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="barber">{{ __('appointments.barber') }}*</x-label>
                            
                            <x-select name="barber" id="barber" class="w-full">
                                @foreach ($barbers as $barber)
                                    <option value="{{ $barber->id }}" @selected($barber->id == $appointment->barber_id)>
                                        {{ $barber->getName() }}
                                    </option>
                                @endforeach
                            </x-select>

                            @error('barber')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror

                            <div class="text-right mt-4 max-md:hidden">* {{ __('auth.required_fields') }}</div>
                        </div>
                    </div>
                @break

                @case('Time Off')
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex gap-2 items-center">
                            <x-input-field type="checkbox" id="fullDayCheckBox" name="full_day" :checked="isset($appointment) ? $appointment->isFullDay() : false"  />
                            <x-label for="fullDayCheckBox">{{ __('barber.full_day_off') }}</x-label>
                        </div>
                        <div class="text-right">* {{ __('auth.required_fields') }}</div>
                    </div>
                @break                    
            @endswitch

            <div class="flex justify-between">
                <div class="flex gap-2">
                    <x-button role="{{ $view == 'Time Off' ? ($action == 'edit' ? 'timeoffMain' : 'timeoffCreateMain') : 'ctaMain' }}" id="submitButton">
                        {{ $action == 'edit' ? __('barber.update') : __('barber.create') }}
                    </x-button>
                    </form>

                    @if ($action == 'edit')
                        <form action="{{ $destroyRoute }}" method="post">
                            @csrf
                            @method('DELETE')
                                <x-button role="destroy">
                                    {{ __('appointments.cancel') }}
                                </x-button>
                        </form>
                    @endif
                </div>

                @if ($action == 'edit' && $view == 'Booking')
                    <div class="text-right md:hidden">* {{ __('auth.required_fields') }}</div>
                @endif
            </div>
    </x-card>

    <x-card class="mb-4">
        <div class="relative">        
            <div id="calendarEventContainer" class="relative w-full h-0 left-0 top-0 lg:translate-y-6"></div>

            <div class="flex text-center mb-4" id="colHeaderContainer">
                <div class="w-1/8"></div>

                @for ($i = 1; $i<=7; $i++)
                    <div class="flex items-center justify-center gap-1 flex-col w-1/8">
                        <span class="text-slate-500">
                            {{ __('barbers.' . strtolower(date('D', strtotime("Sunday + {$i} days")))) }}
                        </span>
                        <span id="{{ lcfirst(date('D', strtotime("Sunday + {$i} days"))) . 'Number'}}">
                        </span>
                    </div>
                @endfor
            </div>

            <div>
                @for ($i = 10; $i<=21; $i++)
                    <div class="text-slate-500 border-slate-300 border-t mb-8">
                        {{ $i }}:00
                    </div>
                @endfor
            </div>

            <div @class(['hidden' => now()->format('G') < 10 || now()->format('G') > 21, 'absolute h-px bg-blue-700 z-20 min-w-full' => true]) id="currentTimeDiv"></div>
            
        </div>
    </x-card>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const appStartInputs = document.querySelectorAll('.appStartInput');
            const appEndInputs = document.querySelectorAll('.appEndInput');

            const appStartDate = document.getElementById('startDate');
            const appStartHour = document.getElementById('startHour');
            const appStartMinute = document.getElementById('startMinute');

            const appEndDate = document.getElementById('endDate');
            const appEndHour = document.getElementById('endHour');
            const appEndMinute = document.getElementById('endMinute');

            let timeDifference = getTimeDifference(appStartDate, appStartHour, appStartMinute, appEndDate, appEndHour, appEndMinute);

            const currentLang = "{{ App::getLocale() }}";

            // CALENDAR VARIABLES
            const colHeaderContainer = document.getElementById('colHeaderContainer');

            const appointments = @json($appointments);

            const barberInput = document.getElementById('barber');
            let date = new Date(appStartDate.value);

            const calendar = document.getElementById('calendarEventContainer');
            const currentTimeDiv = document.getElementById('currentTimeDiv');

            @switch($view)
                @case('Booking')
                    const serviceInput = document.getElementById('service');
                    const priceInput = document.getElementById('price');

                    const services = {!! $services !!};

                    serviceInput.addEventListener('change', function () {
                        selectedService = services.find(service => service.id == serviceInput.value);
                        priceInput.value = selectedService.price;
                        
                        timeDifference = selectedService.duration;
                        startDateTime = getDateTime(appStartDate,appStartHour,appStartMinute);
                        endDateTime = new Date(structuredClone(startDateTime).setMinutes(startDateTime.getMinutes() + timeDifference));
                        
                        appEndDate.value = endDateTime.toISOString().split('T')[0];
                        appEndHour.value = endDateTime.getHours();
                        appEndMinute.value = endDateTime.getMinutes();

                        renderCurrent (calendar, getDateTime(appStartDate,appStartHour,appStartMinute), getDateTime(appEndDate,appEndHour,appEndMinute), getBarberId(barberInput), {{ isset($appointment) ? $appointment->id : 0 }}, '{{ isset($appointment) ? $appointment->user->first_name : '' }}', '{{ $action }}', '{{ $view == 'Time Off' ? 'timeoff' : 'appointment' }}', appointments, currentLang);

                        updateCurrentTimeDiv(currentTimeDiv, 'week');
                    });
                @break

                @case('Time Off')
                    const fullDayInput = document.getElementById('fullDayCheckBox');

                    fullDayInput.addEventListener('change', function () {
                        if (fullDayInput.checked) {
                            appStartHour.value = 10;
                            appStartMinute.value = 0;
                            appEndHour.value = 20;
                            appEndMinute.value = 0;                            
                        } else {
                            appEndHour.value = parseInt(appStartHour.value) + 1;                            
                        }

                        timeDifference = getTimeDifference(appStartDate, appStartHour, appStartMinute, appEndDate, appEndHour, appEndMinute);

                        appStartHour.toggleAttribute('disabled');
                        appStartMinute.toggleAttribute('disabled');
                        appEndHour.toggleAttribute('disabled');
                        appEndMinute.toggleAttribute('disabled');
                        
                        renderCurrent (calendar, getDateTime(appStartDate,appStartHour,appStartMinute), getDateTime(appEndDate,appEndHour,appEndMinute), getBarberId(barberInput), {{ isset($appointment) ? $appointment->id : 0 }}, '{{ isset($appointment) ? $appointment->user->first_name : '' }}', '{{ $action }}', '{{ $view == 'Time Off' ? 'timeoff' : 'appointment' }}', appointments, currentLang);

                        updateCurrentTimeDiv(currentTimeDiv, 'week');
                    });
                @break
            @endswitch

            appStartInputs.forEach(input => {
                input.addEventListener('change', function () {
                    startDateTime = getDateTime(appStartDate,appStartHour,appStartMinute);
                    endDateTime = new Date(structuredClone(startDateTime).setMinutes(startDateTime.getMinutes() + timeDifference));

                    appEndDate.value = endDateTime.toISOString().split('T')[0];
                    appEndMinute.value = endDateTime.getMinutes();

                    if (endDateTime.getHours() >= 10 && endDateTime.getHours() <= 21) {
                        appEndHour.value = endDateTime.getHours();
                    } else {
                        if (endDateTime.getHours() < 10) {
                            appEndHour.value = 21;
                            appEndDate.value = addDays(endDateTime,-1).toISOString().split('T')[0];
                        } else {
                            appEndHour.value = 21;
                        }
                        timeDifference = getTimeDifference(appStartDate, appStartHour, appStartMinute, appEndDate, appEndHour, appEndMinute);
                    }                    

                    renderCurrent (calendar, getDateTime(appStartDate,appStartHour,appStartMinute), getDateTime(appEndDate,appEndHour,appEndMinute), getBarberId(barberInput), {{ isset($appointment) ? $appointment->id : 0 }}, '{{ isset($appointment) ? $appointment->user->first_name : '' }}', '{{ $action }}', '{{ $view == 'Time Off' ? 'timeoff' : 'appointment' }}', appointments, currentLang);

                    updateCurrentTimeDiv(currentTimeDiv, 'week');
                });
            });

            appEndInputs.forEach(input => {
                input.addEventListener('change', function () {
                    timeDifference = getTimeDifference(appStartDate, appStartHour, appStartMinute, appEndDate, appEndHour, appEndMinute);

                    renderCurrent (calendar, getDateTime(appStartDate,appStartHour,appStartMinute), getDateTime(appEndDate,appEndHour,appEndMinute), getBarberId(barberInput), {{ isset($appointment) ? $appointment->id : 0 }}, '{{ isset($appointment) ? $appointment->user->first_name : '' }}', '{{ $action }}', '{{ $view == 'Time Off' ? 'timeoff' : 'appointment' }}', appointments, currentLang);

                    updateCurrentTimeDiv(currentTimeDiv, 'week');
                });
            });
            
            renderDateNumbersNew(colHeaderContainer,date,currentLang);
            updateCurrentTimeDiv(currentTimeDiv, 'week');
            renderExisting(appointments, getBarberId(barberInput), {{ isset($appointment) ? $appointment->id : 0 }}, '{{ $access }}', date, calendar, 'week', currentLang);
            renderCurrent (calendar, getDateTime(appStartDate,appStartHour,appStartMinute), getDateTime(appEndDate,appEndHour,appEndMinute), getBarberId(barberInput), {{ isset($appointment) ? $appointment->id : 0 }}, '{{ isset($appointment) ? $appointment->user->first_name : '' }}', '{{ $action }}', '{{ $view == 'Time Off' ? 'timeoff' : 'appointment' }}', appointments, currentLang);

            appStartDate.addEventListener('change', function () {
                date = new Date(appStartDate.value);
                renderDateNumbersNew(colHeaderContainer,date,currentLang);
                renderExisting(appointments, getBarberId(barberInput), {{ isset($appointment) ? $appointment->id : 0 }}, '{{ $access }}', date, calendar, 'week', currentLang);
                updateCurrentTimeDiv(currentTimeDiv, 'week');
            });

            if (barberInput) {                
                barberInput.addEventListener('change', function () {
                    renderExisting(appointments, getBarberId(barberInput), {{ isset($appointment) ? $appointment->id : 0 }}, '{{ $access }}', date, calendar, 'week', currentLang);
                    renderCurrent (calendar, getDateTime(appStartDate,appStartHour,appStartMinute), getDateTime(appEndDate,appEndHour,appEndMinute), getBarberId(barberInput), {{ isset($appointment) ? $appointment->id : 0 }}, '{{ isset($appointment) ? $appointment->user->first_name : '' }}', '{{ $action }}', '{{ $view == 'Time Off' ? 'timeoff' : 'appointment' }}', appointments, currentLang);
                    updateCurrentTimeDiv(currentTimeDiv, 'week');
                });
            }
        });
        
        function getBarberId(barberInput) {
            if (barberInput) {                
                return barberInput.value;
            } else {
                return {{ isset($appointment) ? $appointment->barber_id : (auth()->user()->barber->id ?? null) }};
            }
        }
    </script>
</x-user-layout>