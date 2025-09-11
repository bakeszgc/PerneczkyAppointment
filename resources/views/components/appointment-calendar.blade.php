@vite(['resources/js/calendar.js'])

<div>
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

    <div class="flex items-center">
        <div>
            <div id="previousWeekButton" class="border border-slate-300 hover:border-slate-700 rounded-md text-slate-500 hover:text-slate-700 flex items-center gap-2 w-fit p-2 pr-3 cursor-pointer transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                <p class="max-sm:hidden">Previous week</p>
            </div>
        </div>
        

        <div class="flex-1 text-sm text-slate-500 text-center">
            <p>Displayed week</p>
            <p>From <span id="fromDate"></span> to <span id="toDate"></span></p>
        </div>

        <div class="flex justify-end">
            <div id="upcomingWeekButton" class="border border-slate-300 hover:border-slate-700 rounded-md text-slate-500 hover:text-slate-700 text-right flex items-center gap-2 w-fit p-2 pl-3 cursor-pointer transition-all">
                <p class="max-sm:hidden">Upcoming week</p>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const monday = document.getElementById('monNumber');
            const tuesday = document.getElementById('tueNumber');
            const wednesday = document.getElementById('wedNumber');
            const thursday = document.getElementById('thuNumber');
            const friday = document.getElementById('friNumber');
            const saturday = document.getElementById('satNumber');
            const sunday = document.getElementById('sunNumber');

            const appointments = @json($calAppointments);

            const previousWeekButton = document.getElementById('previousWeekButton');
            const upcomingWeekButton = document.getElementById('upcomingWeekButton');

            const fromDate = document.getElementById('fromDate');
            const toDate = document.getElementById('toDate');

            let date = new Date();
            const calendar = document.getElementById('calendarEventContainer');

            renderDates(fromDate,toDate,date);
            renderDayNumbers (date, monday, tuesday, wednesday, thursday, friday, saturday, sunday);
            renderExisting(appointments, {{ auth()->user()->barber->id }}, 0, 'barber', date, calendar);

            previousWeekButton.addEventListener('click',function () {
                date = addDays(date,-7);
                renderDates(fromDate,toDate,date);
                renderDayNumbers(date, monday, tuesday, wednesday, thursday, friday, saturday, sunday);
                renderExisting(appointments, {{ auth()->user()->barber->id }}, 0, 'barber', date, calendar);
            });

            upcomingWeekButton.addEventListener('click',function () {
                date = addDays(date,7);
                renderDates(fromDate,toDate,date);
                renderDayNumbers(date, monday, tuesday, wednesday, thursday, friday, saturday, sunday);
                renderExisting(appointments, {{ auth()->user()->barber->id }}, 0, 'barber', date, calendar);
            });
        });
    </script>
</div>