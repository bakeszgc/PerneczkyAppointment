<div>
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

        @if (now()->format('G') >= 10 && now()->format('G') <= 21)
            <div class="absolute h-px bg-blue-700 z-20 min-w-full" id="currentTimeDiv"></div>
        @endif
        
    </div>

    <div class="flex items-center mb-4">
        <div class="flex-grow-0">
            <div id="previousWeekButton" class="border border-slate-300 hover:border-slate-700 rounded-md text-slate-500 hover:text-slate-700 flex items-center gap-2 w-fit p-2 pr-3 cursor-pointer transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                <p class="max-sm:hidden">Previous <span class="viewType">week</span></p>
            </div>
        </div>
        

        <div class="flex-grow text-sm max-md:text-xs text-slate-500 text-center">
            <p>Displayed <span class="viewType">week</span></p>
            <p id="displayWindow"></p>
        </div>

        <div class="flex justify-end flex-grow-0">
            <div id="upcomingWeekButton" class="border border-slate-300 hover:border-slate-700 rounded-md text-slate-500 hover:text-slate-700 text-right flex items-center gap-2 w-fit p-2 pl-3 cursor-pointer transition-all">
                <p class="max-sm:hidden">Upcoming <span class="viewType">week</span></p>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </div>
    </div>

    <div class="text-center">
        <button type="button" id="toggleViewButton" class="p-2 bg-blue-500 hover:bg-blue-700 text-white text-base max-md:text-sm font-bold rounded-md transition-all">
            Switch to <span class="viewType weekly">daily</span> view
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendar = document.getElementById('calendarEventContainer');
            const appointments = @json($calAppointments);
            const access = '{{ $access }}';

            const previousWeekButton = document.getElementById('previousWeekButton');
            const upcomingWeekButton = document.getElementById('upcomingWeekButton');

            const toggleViewButton = document.getElementById('toggleViewButton');
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

            updateCurrentTimeDiv(currentTimeDiv, view);
            renderDateNumbersNew(colHeaderContainer,date);
            renderDates(displayWindow, view, date);
            renderExisting(appointments, {{ $barber->id }}, 0, access, date, calendar, view);

            previousWeekButton.addEventListener('click',function () {
                if (view == 'week') {
                    date = addDays(date,-7);
                    renderDateNumbersNew(colHeaderContainer,date);
                } else {
                    renderBarberNames(colHeaderContainer,barbers);
                    date = addDays(date,-1);
                }

                renderDates(displayWindow, view, date);
                renderExisting(appointments, {{ $barber->id }}, 0, access, date, calendar, view);
                setDivLeft(view);
            });

            upcomingWeekButton.addEventListener('click',function () {
                if (view == 'week') {
                    date = addDays(date,7);
                    renderDateNumbersNew(colHeaderContainer,date);
                } else {
                    renderBarberNames(colHeaderContainer,barbers);
                    date = addDays(date,1);
                }                

                renderDates(displayWindow, view, date);
                renderExisting(appointments, {{ $barber->id }}, 0, access, date, calendar, view);
                setDivLeft(view);
            });

            toggleViewButton.addEventListener('click', () => {
                if (view == 'week') {
                    view = 'day';
                    spanViewType.innerHTML = 'weekly';

                    calendar.innerHTML = "";
                    renderBarberNames(colHeaderContainer,barbers);
                    renderExisting(appointments, {{ $barber->id }}, 0, access, date, calendar, view);
                    setDivLeft(view);

                    timeslots.forEach(ts => {
                        toggleFullWidth(ts, barbers);
                    });

                    toggleFullWidth(currentTimeDiv, barbers);
                    updateCurrentTimeDiv(currentTimeDiv, view);
                    
                } else {
                    view = 'week';
                    spanViewType.innerHTML = 'daily';

                    renderDateNumbersNew(colHeaderContainer,date);
                    renderExisting(appointments, {{ $barber->id }}, 0, access, date, calendar, view);

                    timeslots.forEach(ts => {
                        toggleFullWidth(ts, barbers);
                    });

                    toggleFullWidth(currentTimeDiv, barbers);
                    updateCurrentTimeDiv(currentTimeDiv, view);
                }

                spanViewType.forEach(span => {
                    span.innerHTML = view;
                    span.classList.forEach(spanClass => {
                        if (spanClass == 'weekly') {
                            span.innerHTML = (view == 'week') ? 'daily' : 'weekly';
                        }
                    });
                });

                renderDates(displayWindow, view, date);
            });

            window.addEventListener('resize', () => setDivLeft(view));
        });
    </script>
</div>