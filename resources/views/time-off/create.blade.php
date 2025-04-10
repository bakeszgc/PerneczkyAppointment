<x-user-layout title="Time Off - " currentView="barber">
    <x-breadcrumbs :links="[
        'Time Off' => ''
    ]"/>
    <x-headline class="mb-4">
        Set Your Time Off
    </x-headline>

    <x-card class="mb-4">
        <form action="{{ route('time-off.store') }}" method="POST" x-data="{ isChecked: false }">
            @csrf
            <div class="mb-2 grid grid-cols-2 max-sm:grid-cols-1 gap-2">
                <div class="flex flex-col">
                    <label for="app_start_date" class=" font-bold text-lg mb-2">
                        Start of your time off
                    </label>

                    <div class="flex items-center gap-1">
                        <input type="date" name="app_start_date" id="app_start_date" value="{{ today()->format('Y-m-d') }}" class="border border-slate-300 rounded-md p-2 max-h-10 w-max flex-1 mr-2">
                        <select name="app_start_hour" x-bind:disabled="isChecked" class="border border-slate-300 rounded-md p-2 h-full">
                            @for ($i=10;$i<20;$i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
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
                    <label for="app_end_time" class=" font-bold text-lg mb-2">
                        End of your time off
                    </label>

                    <div class="flex items-center gap-1">
                        <input type="date" name="app_end_date" value="{{ today()->format('Y-m-d') }}" class="border border-slate-300 rounded-md p-2 max-h-10 w-max flex-1 mr-2">
                        <select name="app_end_hour" x-bind:disabled="isChecked" class="border border-slate-300 rounded-md p-2 h-full">
                            @for ($i=10;$i<22;$i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
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
                <input type="checkbox" x-model="isChecked" id="full-day" name="full-day">
                <x-label for="full-day">Full day offs</x-label>
            </div>

            <x-button role="loginMain" :full="true">
                Set Time Off
            </x-button>

        </form>
    </x-card>
    
    <x-card>
        calendar goes here
    </x-card>
</x-user-layout>