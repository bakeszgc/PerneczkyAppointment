<x-user-layout title="Editing Time Off - " currentView="barber">
    <x-breadcrumbs :links="[
        'Bookings' => route('appointments.index'),
        'My Time Off' => route('time-off.show',$appointment),
        'Edit' => ''
    ]"/>

    <div class="flex justify-between items-end mb-4">
        <x-headline>Editing {{ $appointment->barber->display_name ?? $appointment->barber->user->first_name}}'s Time Off</x-headline>
        <x-link-button :link="route('time-off.create')" role="createMain">Add&nbsp;New</x-link-button>
    </div>

    <x-card class="mb-4">
        <h1 class="font-bold text-2xl max-sm:text-lg mb-4">
            {{$appointment->user->first_name . " " . $appointment->user->last_name}} #{{$appointment->id}}
        </h1>

        <form action="{{ route('time-off.update',$appointment) }}" method="POST" x-data="{ isChecked: {{ $appointment->getDuration() >= 600 ? 'true' : 'false' }} }">
            @csrf
            @method('PUT')
            <div class="mb-2 grid grid-cols-2 max-sm:grid-cols-1 gap-2" x-data="{
                app_start_date: '{{ \Carbon\Carbon::parse($appointment->app_start_time)->format('Y-m-d') }}',
                app_start_hour: {{ \Carbon\Carbon::parse($appointment->app_start_time)->hour }},
                app_end_date: '{{ \Carbon\Carbon::parse($appointment->app_end_time)->format('Y-m-d') }}',
                app_end_hour: {{ \Carbon\Carbon::parse($appointment->app_end_time)->hour }},
                diff_in_hours: {{ \Carbon\Carbon::parse($appointment->app_end_time)->hour - \Carbon\Carbon::parse($appointment->app_start_time)->hour }},
                hour_options: Array.from({ length: 10 }, (_, i) => i + 10)}">
                <div class="flex flex-col">
                    <label for="app_start_date" class=" font-bold text-lg mb-2">
                        Start of your time off
                    </label>

                    <div class="flex items-center gap-1">
                        <input type="date" name="app_start_date" id="app_start_date" x-model="app_start_date" class="border border-slate-300 rounded-md p-2 max-h-10 w-max flex-1 mr-2" @change="app_end_date = (new Date(app_start_date)).toISOString().split('T')[0]" />

                        <select name="app_start_hour" x-bind:disabled="isChecked" x-model="app_start_hour" class="border border-slate-300 rounded-md p-2 h-full" @change="
                        app_end_hour = parseInt(app_start_hour) + diff_in_hours;
                        if (!hour_options.includes(app_end_hour)) app_end_hour = 20;">
                            <template x-for="option in hour_options" :key="option">
                                <option :value="option" x-text="option" :selected="option == app_start_hour"></option>
                            </template>
                            <!-- @for ($i=10;$i<20;$i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor -->
                        </select>

                        <select name="app_start_minute" x-bind:disabled="isChecked" x-model="app_start_minute" class="border border-slate-300 rounded-md p-2 h-full">
                            @for ($i=0;$i<60;$i+=15)
                                <option value="{{ $i }}" {{ $i == \Carbon\Carbon::parse($appointment->app_start_time)->minute ? "selected=\"selected\"" : ''}}>
                                    {{ $i == 0 ? '00' : $i}}
                                </option>
                            @endfor
                        </select>
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
                    <label for="app_end_date" class=" font-bold text-lg mb-2">
                        End of your time off
                    </label>

                    <div class="flex items-center gap-1">
                        <input type="date" name="app_end_date" id="app_end_date" x-model="app_end_date" class="border border-slate-300 rounded-md p-2 max-h-10 w-max flex-1 mr-2" @change="app_start_date = (new Date(app_end_date)).toISOString().split('T')[0]" />

                        <select name="app_end_hour" x-bind:disabled="isChecked" x-model="app_end_hour" class="border border-slate-300 rounded-md p-2 h-full" @change="
                        diff_in_hours = app_end_hour - app_start_hour;">
                            <template x-for="option in hour_options" :key="option">
                                <option :value="option" x-text="option" :selected="option === app_end_hour"></option>
                            </template>
                            <option value="20">20</option>
                            <!-- @for ($i=10;$i<22;$i++)
                                <option value="{{ $i }}" {{ $i == 11 ? "selected=\"selected\"" : "" }}>
                                    {{ $i }}
                                </option>
                            @endfor -->
                        </select>

                        <select name="app_end_minute" x-bind:disabled="isChecked" x-model="app_end_minute" class="border border-slate-300 rounded-md p-2 h-full">
                            @for ($i=0;$i<60;$i+=15)
                                <option value="{{ $i }}" {{ $i == \Carbon\Carbon::parse($appointment->app_end_time)->minute ? "selected=\"selected\"" : ''}}>
                                    {{ $i == 0 ? '00' : $i}}
                                </option>
                            @endfor
                        </select>
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

            <div class="flex gap-2 mb-4">
                <input type="checkbox" x-model="isChecked" id="full_day" name="full_day">
                <x-label for="full_day">Full day off</x-label>
            </div>

            <x-button role="loginMain" :full="true">
                Update Time Off
            </x-button>

        </form>
    </x-card>

</x-user-layout>