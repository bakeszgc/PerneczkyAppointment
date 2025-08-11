@php
    use Carbon\Carbon;
    $view = $view ?? 'barber';
@endphp

<x-user-layout title="Editing {{$appointment->user->first_name}}'s Appointment - " currentView="{{ $view }}">

    <x-breadcrumbs :links="$view == 'admin' ? [
        'Admin Dashboard' => route('admin'),
        'Bookings' => route('bookings.index'),
        $appointment->user->first_name . '\'s Booking' => route('bookings.show',$appointment),
        'Edit' => ''
    ] : [
        'Bookings' => route('appointments.index'),
        $appointment->user->first_name . '\'s Booking' => route('appointments.show',$appointment),
        'Edit' => ''
    ]"/>

    <x-headline class="mb-4">
        Editing {{ $appointment->user->first_name }}'s Booking
    </x-headline>

    <x-card>
        <div x-data="{
            services: {{json_encode($services)}},
            selectedServiceId: {{ $appointment->service_id }},
            servicePrice: {{ $appointment->price }},

            updateService() {
                const selectedService = this.services.find(service => service.id == this.selectedServiceId);
                this.servicePrice = selectedService ? selectedService.price : '';

                if (selectedService) {
                    const totalMinutes = (this.appStartHour * 60 + this.appStartMinute) + selectedService.duration;
                    this.appEndHour = Math.floor(totalMinutes / 60) % 24;
                    this.appEndMinute = totalMinutes % 60;
                }
            }
        }">
            <h1 class="font-bold text-2xl max-sm:text-lg mb-4">
                {{$appointment->user->first_name . " " . $appointment->user->last_name}} #{{$appointment->id}}
            </h1>
            
            <form action="{{$view == 'admin' ? route('bookings.update',$appointment) : route('appointments.update',$appointment)}}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4 grid grid-cols-2 max-sm:grid-cols-1 gap-4">
                    <div>
                        <x-label for="service">
                            Service
                        </x-label>

                        <x-select name="service" id="service" x-model="selectedServiceId" @change="updateService()" class="w-full">
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" {{ $service->id == $appointment->service_id ? 'selected' : '' }}>
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

                        <x-input-field type="number" name="price" id="price" :value="$appointment->price" x-model="servicePrice" class="w-full h-fit"/>

                        @error('price')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4 grid grid-cols-2 max-sm:grid-cols-1 gap-4">
                    <div class="flex flex-col">
                        <x-label for="startDate">
                            Booking's start time
                        </x-label>

                        <div class="flex items-center gap-1">
                            <x-input-field type="date" name="app_start_date" id="startDate" value="{{ Carbon::parse($appointment->app_start_time)->format('Y-m-d') }}" class="flex-1 mr-2 appStartInput" />

                            <x-select name="app_start_hour" id="startHour" class="appStartInput">
                                @for ($i=10;$i<20;$i++)
                                    <option value="{{ $i }}" {{ $i == Carbon::parse($appointment->app_start_time)->format('G') ? "selected" : ''}}>{{ $i }}</option>
                                @endfor
                            </x-select>

                            <x-select name="app_start_minute" id="startMinute" class="appStartInput">
                                @for ($i=0;$i<60;$i+=15)
                                    <option value="{{ $i }}" {{ $i == Carbon::parse($appointment->app_start_time)->format('i') ? "selected" : ''}}>{{ $i == 0 ? '00' : $i}}</option>
                                @endfor
                            </x-select>
                        </div>

                        <p>
                            @if ($previous && $previous->app_end_time >= Carbon::parse($appointment->app_end_time)->startOfDay())
                                Your previous booking ends at {{Carbon::parse($previous->app_end_time)->format('G:i')}} on {{Carbon::parse($previous->app_end_time)->format('jS F')}}
                            @else
                                You don't have any previous bookings on {{Carbon::parse($appointment->app_end_time)->format('jS F')}}
                            @endif
                        </p>

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
                            Booking's end time
                        </x-label>

                        <div class="flex items-center gap-1">
                            <x-input-field type="date" name="app_end_date" id="endDate" value="{{ Carbon::parse($appointment->app_end_time)->format('Y-m-d') }}" class="flex-1 mr-2 appEndInput" />

                            <x-select name="app_end_hour" id="endHour" class="appEndInput">
                                @for ($i=10;$i<22;$i++)
                                    <option value="{{ $i }}" {{ $i == Carbon::parse($appointment->app_end_time)->format('G') ? "selected" : ''}}>{{ $i }}</option>
                                @endfor
                            </x-select>

                            <x-select name="app_end_minute" id="endMinute" class="appEndInput">
                                @for ($i=0;$i<60;$i+=15)
                                    <option value="{{ $i }}" {{ $i == Carbon::parse($appointment->app_end_time)->format('i') ? "selected" : ''}}>
                                        {{ $i == 0 ? '00' : $i}}
                                    </option>
                                @endfor
                            </x-select>
                        </div>

                        <p>
                            @if ($next && $next->app_start_time <= Carbon::parse($appointment->app_end_time)->addDay()->startOfDay())
                                Your next booking starts at {{Carbon::parse($next->app_start_time)->format('G:i')}} on {{Carbon::parse($next->app_start_time)->format('jS F')}}
                            @else
                                You don't have any upcoming bookings on {{Carbon::parse($appointment->app_start_time)->format('jS F')}}
                            @endif
                        </p>
                        
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
                
                <div @class(['mb-4','grid grid-cols-2 gap-4' => $view == 'admin'])>
                    <div>
                        <x-label for="comment">Comment</x-label>

                        <x-textarea name="comment" id="comment" class="w-full">{{old('comment') ?? $appointment->comment}}</x-textarea>

                        @error('comment')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    @if ($view == 'admin')
                        <div>
                            <x-label for="barber">Barber</x-label>
                            
                            <x-select name="barber" id="barber" class="w-full">
                                @foreach ($barbers as $barber)
                                    <option value="{{ $barber->id }}" {{ $barber->id == $appointment->barber_id ? 'selected' : '' }}>{{ $barber->getName() }}</option>
                                @endforeach
                            </x-select>
                        </div>
                    @endif
                </div>

                <div class="flex gap-2">
                    <x-button role="createMain">
                        Update
                    </x-button>
                    </form>

                    <form action="{{ $view == 'admin' ? route('bookings.destroy', $appointment) : route('appointments.destroy',$appointment) }}">
                    <x-button role="destroy">
                        Cancel
                    </x-button>
                    </form>
                </div>
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

            appStartInputs.forEach(input => {
                input.addEventListener('change', function () {
                    startDateTime = getDateTime(appStartDate,appStartHour,appStartMinute);
                    endDateTime = new Date(structuredClone(startDateTime).setMinutes(startDateTime.getMinutes() + timeDifference));

                    appEndDate.value = endDateTime.toISOString().split('T')[0];
                    appEndHour.value = endDateTime.getHours();
                    appEndMinute.value = endDateTime.getMinutes();
                });
            });

            appEndInputs.forEach(input => {
                input.addEventListener('change', function () {
                    timeDifference = getTimeDifference(appStartDate, appStartHour, appStartMinute, appEndDate, appEndHour, appEndMinute);
                });
            });
        });

        function getDateTime(date, hour, minute) {
            return new Date((date.value).concat(" ",hour.value,":",minute.value));
        }

        function getTimeDifference(appStartDate, appStartHour, appStartMinute, appEndDate, appEndHour, appEndMinute) {
            startDateTime = getDateTime(appStartDate,appStartHour,appStartMinute);
            endDateTime = getDateTime(appEndDate,appEndHour,appEndMinute);
            return (endDateTime - startDateTime) / 1000 / 60;
        }
    </script>
</x-user-layout>