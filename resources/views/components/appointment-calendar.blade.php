<div {{ $attributes->merge(['class' => '']) }}>
    <div class="relative overflow-auto mb-4">        
        <div id="calendarEventContainer" class="relative w-full h-0 left-0 top-0 lg:translate-y-6"></div>

        <div class="flex text-center mb-4" id="colHeaderContainer">
            <div class="w-1/8"></div>

            @for ($i = 1; $i<=7; $i++)
                <div class="flex items-center justify-center gap-1 flex-col w-1/8">
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
                <div class="text-slate-500 border-slate-300 border-t mb-8 min-w-full timeslot">
                    {{ $i }}:00
                </div>
            @endfor
        </div>


        <div @class(['hidden' => now()->format('G') < 10 || now()->format('G') > 21, 'absolute h-px bg-blue-700 z-20 min-w-full' => true]) id="currentTimeDiv"></div>
        
    </div>

    <div class="grid grid-cols-3 max-sm:grid-cols-5 mb-4">
        <div>
            <div id="previousWeekButton" class="border border-slate-300 hover:border-slate-700 rounded-md text-slate-500 hover:text-slate-700 flex items-center gap-2 w-fit p-2 pr-3 cursor-pointer transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                <p class="max-sm:hidden">
                    {{ __('barber.previous') }} <span class="viewType">{{ __('barber.week') }}</span>
                </p>
            </div>
        </div>
        

        <div class="max-sm:col-span-3 text-sm max-md:text-xs text-slate-500 text-center flex flex-col justify-center">
            <p>
                {{ __('barber.displayed') }} <span class="viewType">{{ __('barber.week') }}</span>
            </p>
            <p id="displayWindow"></p>
        </div>

        <div class="flex justify-end">
            <div id="upcomingWeekButton" class="border border-slate-300 hover:border-slate-700 rounded-md text-slate-500 hover:text-slate-700 text-right flex items-center gap-2 w-fit p-2 pl-3 cursor-pointer transition-all">
                <p class="max-sm:hidden">
                    {{ __('barber.upcoming') }} <span class="viewType">{{ __('barber.week') }}</span>
                </p>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </div>
    </div>

    <div class="text-center flex justify-center items-center gap-4">
        <x-button role="ctaMain" id="toggleViewButton">
            {{ __('barber.switch_to') }}
            <span class="viewType weekly">
                {{ __('barber.daily') }}
            </span>
            {{ __('barber.view') }}
        </x-button>

        <x-select name="barberSelect" id="barberSelect" :disabled="$defaultView=='day'" class="text-sm">
            <option value="empty">Select a barber</option>
            @foreach ($barbers as $b)
                <option value="{{ $b->id }}" @selected((isset($barber) && $barber->id == $b->id) ?? auth()->user()?->barber->id == $b->id)>{{ $b->getName() }}</option>
            @endforeach
        </x-select>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendar = document.getElementById('calendarEventContainer');
            const appointments = @json($calAppointments);
            const access = '{{ $access }}';

            const previousWeekButton = document.getElementById('previousWeekButton');
            const upcomingWeekButton = document.getElementById('upcomingWeekButton');

            const toggleViewButton = document.getElementById('toggleViewButton');
            const barberSelect = document.getElementById('barberSelect');

            const colHeaderContainer = document.getElementById('colHeaderContainer');

            const spanViewType = document.querySelectorAll('.viewType');
            let view = 'week';

            const timeslots = document.querySelectorAll('.timeslot');
            const currentTimeDiv = document.getElementById('currentTimeDiv');

            const barbers = @json($barbers);

            const fromDate = document.getElementById('fromDate');
            const toDate = document.getElementById('toDate');
            const displayWindow = document.getElementById('displayWindow');

            let date = new Date();

            const currentLang = "{{ App::getLocale() }}";            

            updateCurrentTimeDiv(currentTimeDiv, view);
            renderDateNumbersNew(colHeaderContainer,date,currentLang);
            renderDates(displayWindow, view, date, currentLang);
            renderExisting(appointments, barberSelect.value, 0, access, date, calendar, view);

            previousWeekButton.addEventListener('click',function () {
                if (view == 'week') {
                    date = addDays(date,-7);
                    renderDateNumbersNew(colHeaderContainer,date,currentLang);
                } else {
                    renderBarberNames(colHeaderContainer,barbers);
                    date = addDays(date,-1);
                }

                renderDates(displayWindow, view, date, currentLang);
                renderExisting(appointments, barberSelect.value, 0, access, date, calendar, view);
                setDivLeft(view);
            });

            upcomingWeekButton.addEventListener('click',function () {
                if (view == 'week') {
                    date = addDays(date,7);
                    renderDateNumbersNew(colHeaderContainer,date,currentLang);
                } else {
                    renderBarberNames(colHeaderContainer,barbers);
                    date = addDays(date,1);
                }                

                renderDates(displayWindow, view, date, currentLang);
                renderExisting(appointments, barberSelect.value, 0, access, date, calendar, view);
                setDivLeft(view);
            });

            barberSelect.addEventListener('change', () => {
                renderDateNumbersNew(colHeaderContainer,date,currentLang);
                renderDates(displayWindow, view, date, currentLang);
                renderExisting(appointments, barberSelect.value, 0, access, date, calendar, view);
            });

            toggleViewButton.addEventListener('click', () => {
                if (view == 'week') {
                    view = 'day';
                    switchToDailyView(colHeaderContainer,date,appointments,barberSelect.value,access,calendar,view,timeslots,barbers,currentTimeDiv,barberSelect);
                    
                } else {
                    view = 'week';
                    switchToWeeklyView(colHeaderContainer,date,appointments,barberSelect.value,access,calendar,view,timeslots,barbers,currentTimeDiv,barberSelect,currentLang);
                }

                spanViewType.forEach(span => {
                    if (view == 'week') {
                        span.innerHTML = "{{ __('barber.week') }}";
                    } else {
                        span.innerHTML = "{{ __('barber.day') }}";
                    }
                    
                    span.classList.forEach(spanClass => {
                        if (spanClass == 'weekly') {
                            span.innerHTML = (view == 'week') ? '{{ __('barber.daily') }}' : '{{ __('barber.weekly') }}';
                        }
                    });
                });

                renderDates(displayWindow, view, date, currentLang);
            });

            window.addEventListener('resize', () => setDivLeft(view));

            @if ($defaultView == 'day')
                view = 'day';

                switchToDailyView(colHeaderContainer,date,appointments,barberSelect.value,access,calendar,view,timeslots,barbers,currentTimeDiv,barberSelect);

                spanViewType.forEach(span => {
                    span.innerHTML = "{{ __('barber.day') }}";
                    span.classList.forEach(spanClass => {
                        if (spanClass == 'weekly') {
                            span.innerHTML = (view == 'week') ? 'daily' : 'weekly';
                        }
                    });
                });
            @endif
        });
    </script>
</div>