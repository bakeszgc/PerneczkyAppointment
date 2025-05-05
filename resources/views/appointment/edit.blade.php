<x-user-layout title="Editing {{$appointment->user->first_name}}'s Appointment - " currentView="barber">
    <x-breadcrumbs :links="[
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

            updateServicePrice() {
                const selectedService = this.services.find(service => service.id == this.selectedServiceId);
                this.servicePrice = selectedService ? selectedService.price : '';
            }
        }" x-init="updateServicePrice()">
            <h1 class="font-bold text-2xl max-sm:text-lg mb-4">
                {{$appointment->user->first_name . " " . $appointment->user->last_name}} #{{$appointment->id}}
            </h1>
            
            <form action="{{route('appointments.update',$appointment)}}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4 grid grid-cols-2 max-sm:grid-cols-1 gap-2">
                    <div>
                        <label for="service" class=" font-bold text-lg">
                            Service
                        </label>
                        <select name="service" id="service" class="border border-slate-300 rounded-md p-2 w-full" x-model="selectedServiceId" @change="updateServicePrice()">
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" {{ $service->id == $appointment->service_id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('service')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="price" class=" font-bold text-lg">
                            Price (in HUF)
                        </label>
                        <x-input-field type="number" name="price" id="price" :value="$appointment->price" x-model="servicePrice" class="w-full max-h-9"/>
                        @error('price')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4 grid grid-cols-2 max-sm:grid-cols-1 gap-2">
                    <div class="flex flex-col">
                        <label for="app_start_date" class=" font-bold text-lg">
                            Booking's start time
                        </label>

                        <div class="flex items-center gap-1">
                            <input type="date" name="app_start_date" id="app_start_date" value="{{ \Carbon\Carbon::parse($appointment->app_start_time)->format('Y-m-d') }}" class="border border-slate-300 rounded-md p-2 max-h-10 w-max flex-1 mr-2">
                            <select name="app_start_hour" class="border border-slate-300 rounded-md p-2 h-full">
                                @for ($i=10;$i<20;$i++)
                                    <option value="{{ $i }}" {{ $i == \Carbon\Carbon::parse($appointment->app_start_time)->format('G') ? "selected=\"selected\"" : ''}}>{{ $i }}</option>
                                @endfor
                            </select>
                            <select name="app_start_minute" class="border border-slate-300 rounded-md p-2 h-full">
                                @for ($i=0;$i<60;$i+=15)
                                    <option value="{{ $i }}" {{ $i == \Carbon\Carbon::parse($appointment->app_start_time)->format('i') ? "selected=\"selected\"" : ''}}>{{ $i == 0 ? '00' : $i}}</option>
                                @endfor
                            </select>
                        </div>

                        <p>
                            @if ($previous->app_end_time >= \Carbon\Carbon::parse($appointment->app_end_time)->startOfDay())
                                Your previous booking ends at {{\Carbon\Carbon::parse($previous->app_end_time)->format('G:i')}} on this day
                            @else
                                You don't have any previous bookings on this day
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
                        <label for="app_end_time" class=" font-bold text-lg">
                            Booking's end time
                        </label>

                        <div class="flex items-center gap-1">
                            <input type="date" name="app_end_date" value="{{ \Carbon\Carbon::parse($appointment->app_end_time)->format('Y-m-d') }}" class="border border-slate-300 rounded-md p-2 max-h-10 w-max flex-1 mr-2">
                            <select name="app_end_hour" class="border border-slate-300 rounded-md p-2 h-full">
                                @for ($i=10;$i<22;$i++)
                                    <option value="{{ $i }}" {{ $i == \Carbon\Carbon::parse($appointment->app_end_time)->format('G') ? "selected=\"selected\"" : ''}}>{{ $i }}</option>
                                @endfor
                            </select>
                            <select name="app_end_minute" class="border border-slate-300 rounded-md p-2 h-full">
                                @for ($i=0;$i<60;$i+=15)
                                    <option value="{{ $i }}" {{ $i == \Carbon\Carbon::parse($appointment->app_end_time)->format('i') ? "selected=\"selected\"" : ''}}>
                                        {{ $i == 0 ? '00' : $i}}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <p>
                            @if ($next->app_start_time <= \Carbon\Carbon::parse($appointment->app_end_time)->addDay()->startOfDay())
                                Your next booking starts at {{\Carbon\Carbon::parse($next->app_start_time)->format('G:i')}} on this day
                            @else
                                You don't have any upcoming bookings on this day
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
                
                <div class="mb-4">
                    <label for="comment" class="font-bold text-lg">Comment</label>
                    <textarea name="comment" id="comment" class="border border-slate-300 rounded-md p-2 w-full">{{$appointment->comment}}</textarea>
                    @error('comment')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>

                <div class="flex gap-2">
                    <x-button role="createMain">
                        Update
                    </x-button>
                    </form>

                    <form action="{{ route('appointments.destroy',$appointment) }}">
                    <x-button role="destroy">
                        Cancel
                    </x-button>
                    </form>
                </div>
        
        </div>
    </x-card>
</x-user-layout>