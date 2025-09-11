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
                            $title = "Editing " . $appointment->barber->getName() . "'s Time Off";
                            $formRoute = route('admin-time-offs.update',$appointment);
                            $destroyRoute = route('admin-time-offs.destroy',$appointment);
                            $breadcrumbLinks = [
                                'Admin Dashboard' => route('admin'),
                                'Time Offs' => route('admin-time-offs.index'),
                                $appointment->barber->getName() . '\'s Time Off' => route('admin-time-offs.show',$appointment),
                                'Edit' => ''
                            ];
                        break;

                        case 'create':
                            $title = "Set A New Time Off";
                            $formRoute = route('admin-time-offs.store');
                            $breadcrumbLinks = [
                                'Admin Dashboard' => route('admin'),
                                'Time Offs' => route('admin-time-offs.index'),
                                'New Time Off' => ''
                            ];
                        break;
                    }
                    
                break;

                case 'Booking':
                    $title = "Editing " . $appointment->user->first_name . "'s Booking";
                    $formRoute = route('bookings.update',$appointment);
                    $destroyRoute = route('bookings.destroy',$appointment);
                    $breadcrumbLinks = [
                        'Admin Dashboard' => route('admin'),
                        'Bookings' => route('bookings.index'),
                        $appointment->user->first_name . '\'s Booking' => route('bookings.show',$appointment),
                        'Edit' => ''
                    ];
                break;
            }
        break;

        case 'barber':
            switch ($view) {
                case 'Time Off':
                    switch ($action) {
                        case 'edit':
                            $title = "Editing Your Time Off";
                            $formRoute = route('time-offs.update',$appointment);
                            $destroyRoute = route('time-offs.destroy',$appointment);
                            $breadcrumbLinks = [
                                'Time Offs' => route('time-offs.index'),
                                'Your Time Off' => route('time-offs.show',$appointment),
                                'Edit' => ''
                            ];
                        break;

                        case 'create':
                            $title = "Set Your Time Off";
                            $formRoute = route('time-offs.store');
                            $breadcrumbLinks = [
                                'Time Offs' => route('time-offs.index'),
                                'New Time Off' => ''
                            ];
                        break;
                    }
                break;

                case 'Booking':
                    $title = "Editing " . $appointment->user->first_name . "'s Booking";
                    $formRoute = route('appointments.update',$appointment);
                    $destroyRoute = route('appointments.destroy',$appointment);
                    $breadcrumbLinks = [
                        'Bookings' => route('appointments.index'),
                        $appointment->user->first_name . '\'s Booking' => route('appointments.show',$appointment),
                        'Edit' => ''
                    ];
                break;
            }
        break;
    }
@endphp

@vite(['resources/js/calendar.js'])

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
                    <div>
                        <x-label for="service">
                            Service
                        </x-label>

                        <x-select name="service" id="service" class="w-full">
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" @selected($service->id == $appointment->service_id)>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </x-select>

                        @error('service')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    <div>
                        <x-label for="price">
                            Price (in HUF)
                        </x-label>

                        <x-input-field type="number" name="price" id="price" :value="$appointment->price" class="w-full h-fit"/>

                        @error('price')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                @endif

                @if ($access == 'admin' && $view == 'Time Off' && $action == 'create')
                    <div class=" col-span-2">
                        <x-label for="barber">Barber</x-label>
                        
                        <x-select name="barber" id="barber" class="w-full">
                            <option value="empty"></option>
                            @foreach ($barbers as $barber)
                                <option value="{{ $barber->id }}" @selected(old('barber'))>
                                    {{ $barber->getName() }}
                                </option>
                            @endforeach
                        </x-select>

                        @error('barber')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                @endif

                <div class="flex flex-col">
                    <x-label for="startDate">
                        {{ $view == 'Booking' ? 'Booking' : 'Time off' }}'s start time
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

                <div class="flex flex-col">
                    <x-label for="endDate">
                        {{ $view == 'Booking' ? 'Booking' : 'Time off' }}'s end time
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
                    <div @class(['mb-4','grid grid-cols-2 gap-4' => $access == 'admin'])>
                        <div class="flex flex-col">
                            <x-label for="comment">Comment</x-label>

                            <x-input-field type="textarea" name="comment" id="comment">{{old('comment') ?? $appointment->comment}}</x-input-field>

                            @error('comment')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror
                        </div>

                        @if ($access == 'admin')
                            <div>
                                <x-label for="barber">Barber</x-label>
                                
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
                            </div>
                        @endif
                    </div>
                @break

                @case('Time Off')
                    <div class="flex gap-2 items-center mb-4">
                        <x-input-field type="checkbox" id="fullDayCheckBox" name="full_day" :checked="isset($appointment) ? $appointment->isFullDay() : false"  />
                        <x-label for="fullDayCheckBox">Full day off</x-label>
                    </div>
                @break                    
            @endswitch

            <div class="flex gap-2">
                <x-button role="{{ $view == 'Time Off' ? ($action == 'edit' ? 'timeoffMain' : 'timeoffCreateMain') : 'ctaMain' }}" id="submitButton">
                    {{ $action == 'edit' ? 'Update' : 'Create' }}
                </x-button>
                </form>

                @if ($action == 'edit')
                    <form action="{{ $destroyRoute }}" method="post">
                        @csrf
                        @method('DELETE')
                            <x-button role="destroy">
                                Cancel
                            </x-button>
                    </form>
                @endif
            </div>
    </x-card>

    <x-card>
        <div class="relative">        
            <div id="calendarEventContainer" class="relative w-full h-0 left-0 top-0 max-lg:-translate-y-3"></div>

            <div class="flex text-center mb-4">
                <div class="w-1/8"></div>
                @for ($i = 1; $i<=7; $i++)
                    <div class="flex items-center justify-center gap-1 max-lg:flex-col w-1/8">
                        <span class="text-slate-500">
                            {{ date('D', strtotime("Sunday + {$i} days")) }}
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

            @if (now()->format('G') >= 10 && now()->format('G') <= 21)
                <div style="position: absolute; width: 100%; height: 1px; background-color: blue; top: {{ 53/60 * (now()->format('G') * 60 + now()->format('i')) - 486 }}px; z-index: 20;">
                </div>
            @endif
            
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

            // CALENDAR VARIABLES
            const monday = document.getElementById('monNumber');
            const tuesday = document.getElementById('tueNumber');
            const wednesday = document.getElementById('wedNumber');
            const thursday = document.getElementById('thuNumber');
            const friday = document.getElementById('friNumber');
            const saturday = document.getElementById('satNumber');
            const sunday = document.getElementById('sunNumber');

            const appointments = @json($appointments);

            const barberInput = document.getElementById('barber');
            let date = new Date(appStartDate.value);

            const calendar = document.getElementById('calendarEventContainer');

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

                        renderCurrent (calendar, getDateTime(appStartDate,appStartHour,appStartMinute), getDateTime(appEndDate,appEndHour,appEndMinute), getBarberId(barberInput), {{ isset($appointment) ? $appointment->id : 0 }}, '{{ isset($appointment) ? $appointment->user->first_name : '' }}', '{{ $action }}', '{{ $view == 'Time Off' ? 'timeoff' : 'appointment' }}', appointments);
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

                        appStartHour.toggleAttribute('disabled');
                        appStartMinute.toggleAttribute('disabled');
                        appEndHour.toggleAttribute('disabled');
                        appEndMinute.toggleAttribute('disabled');
                        
                        renderCurrent (calendar, getDateTime(appStartDate,appStartHour,appStartMinute), getDateTime(appEndDate,appEndHour,appEndMinute), getBarberId(barberInput), {{ isset($appointment) ? $appointment->id : 0 }}, '{{ isset($appointment) ? $appointment->user->first_name : '' }}', '{{ $action }}', '{{ $view == 'Time Off' ? 'timeoff' : 'appointment' }}', appointments);
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

                    renderCurrent (calendar, getDateTime(appStartDate,appStartHour,appStartMinute), getDateTime(appEndDate,appEndHour,appEndMinute), getBarberId(barberInput), {{ isset($appointment) ? $appointment->id : 0 }}, '{{ isset($appointment) ? $appointment->user->first_name : '' }}', '{{ $action }}', '{{ $view == 'Time Off' ? 'timeoff' : 'appointment' }}', appointments);
                });
            });

            appEndInputs.forEach(input => {
                input.addEventListener('change', function () {
                    timeDifference = getTimeDifference(appStartDate, appStartHour, appStartMinute, appEndDate, appEndHour, appEndMinute);

                    renderCurrent (calendar, getDateTime(appStartDate,appStartHour,appStartMinute), getDateTime(appEndDate,appEndHour,appEndMinute), getBarberId(barberInput), {{ isset($appointment) ? $appointment->id : 0 }}, '{{ isset($appointment) ? $appointment->user->first_name : '' }}', '{{ $action }}', '{{ $view == 'Time Off' ? 'timeoff' : 'appointment' }}', appointments);
                });
            });
            
            renderDayNumbers (date, monday, tuesday, wednesday, thursday, friday, saturday, sunday);
            renderExisting(appointments, getBarberId(barberInput), {{ isset($appointment) ? $appointment->id : 0 }}, date, calendar);
            renderCurrent (calendar, getDateTime(appStartDate,appStartHour,appStartMinute), getDateTime(appEndDate,appEndHour,appEndMinute), getBarberId(barberInput), {{ isset($appointment) ? $appointment->id : 0 }}, '{{ isset($appointment) ? $appointment->user->first_name : '' }}', '{{ $action }}', '{{ $view == 'Time Off' ? 'timeoff' : 'appointment' }}', appointments);

            appStartDate.addEventListener('change', function () {
                date = new Date(appStartDate.value);
                renderDayNumbers(date, monday, tuesday, wednesday, thursday, friday, saturday, sunday);
                renderExisting(appointments, getBarberId(barberInput), {{ isset($appointment) ? $appointment->id : 0 }}, date, calendar);
            });

            if (barberInput) {
                barberInput.addEventListener('change', function () {
                    renderExisting(appointments, getBarberId(barberInput), {{ isset($appointment) ? $appointment->id : 0 }}, date, calendar);
                    renderCurrent (calendar, getDateTime(appStartDate,appStartHour,appStartMinute), getDateTime(appEndDate,appEndHour,appEndMinute), getBarberId(barberInput), {{ isset($appointment) ? $appointment->id : 0 }}, '{{ isset($appointment) ? $appointment->user->first_name : '' }}', '{{ $action }}', '{{ $view == 'Time Off' ? 'timeoff' : 'appointment' }}', appointments);
                });
            }
        });
        
        function getBarberId(barberInput) {
            if (barberInput) {
                return barberInput.value;
            } else {
                return {{ isset($appointment) ? $appointment->barber_id : auth()->user()->barber->id }};
            }
        }
    </script>
</x-user-layout>