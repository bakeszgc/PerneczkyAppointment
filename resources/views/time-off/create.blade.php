<x-user-layout title="Time Off" currentView="barber">
    <x-breadcrumbs :links="[
        'Time Off' => ''
    ]"/>
    <x-headline class="mb-4">
        Set Your Time Off
    </x-headline>

    <x-card class="mb-4">
        <form action="{{ route('time-offs.store') }}" method="POST" x-data="{ isChecked: false }">
            @csrf
            <div class="mb-2 grid grid-cols-2 max-sm:grid-cols-1 gap-2" x-data="{
                app_start_date: (new Date()).toISOString().split('T')[0],
                app_start_hour: 10,
                app_end_date: (new Date()).toISOString().split('T')[0],
                app_end_hour: 11,
                diff_in_days: 0,
                diff_in_hours: 1,
                hour_options: Array.from({ length: 10 }, (_, i) => i + 10)}">
                <div class="flex flex-col">
                    <label for="app_start_date" class=" font-bold text-lg mb-2">
                        Start of your time off
                    </label>

                    <div class="flex items-center gap-1">
                        <input type="date" name="app_start_date" id="app_start_date" x-model="app_start_date" class="border border-slate-300 rounded-md p-2 max-h-10 w-max flex-1 mr-2" @change="
                        app_end_date = (new Date(new Date(app_start_date).setDate(new Date(app_start_date).getDate() + diff_in_days))).toISOString().split('T')[0]" />

                        <select name="app_start_hour" x-bind:disabled="isChecked" x-model="app_start_hour" class="border border-slate-300 rounded-md p-2 h-full" @change="
                        app_end_hour = parseInt(app_start_hour) + diff_in_hours;
                        if (!hour_options.includes(app_end_hour)) app_end_hour = 20;">
                            <template x-for="option in hour_options" :key="option">
                                <option :value="option" x-text="option"></option>
                            </template>
                            <!-- @for ($i=10;$i<20;$i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor -->
                        </select>

                        <select name="app_start_minute" x-bind:disabled="isChecked" class="border border-slate-300 rounded-md p-2 h-full">
                            @for ($i=0;$i<60;$i+=15)
                                <option value="{{ $i }}">{{ $i == 0 ? '00' : $i}}</option>
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
                        <input type="date" name="app_end_date" id="app_end_date" x-model="app_end_date" class="border border-slate-300 rounded-md p-2 max-h-10 w-max flex-1 mr-2" @change="
                        diff_in_days = Math.ceil((new Date(app_end_date) - new Date(app_start_date)) / (1000 * 60 * 60 * 24));">

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

                        <select name="app_end_minute" x-bind:disabled="isChecked" class="border border-slate-300 rounded-md p-2 h-full">
                            @for ($i=0;$i<60;$i+=15)
                                <option value="{{ $i }}">
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
                <x-label for="full_day">Full day offs</x-label>
            </div>

            <x-button role="timeoffMain" :full="true">
                Set time off
            </x-button>

        </form>
    </x-card>
    
    <x-card>
        calendar goes here
    </x-card>
</x-user-layout>