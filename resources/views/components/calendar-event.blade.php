<div style="{{ "position: absolute; width: 12.5%;
top: " . 53/60 * \Carbon\Carbon::parse($appointment->app_start_time)->format('G')*60 +\Carbon\Carbon::parse($appointment->app_start_time)->format('i') -486 .
"px; left: calc(" . (\Carbon\Carbon::parse($appointment->app_start_time)->format('N')) * 12.5 . "%);
height: calc(" . \Carbon\Carbon::parse($appointment->app_start_time)->diffInMinutes(\Carbon\Carbon::parse($appointment->app_end_time)) / 60 * 53 .
"px); padding: 0.125rem;" }}">

    <a href="{{ route('appointments.show',$appointment) }}">
        <div @class([
            'bg-blue-100 hover:bg-blue-200 border border-blue-300 transition-all text-blue-600 h-full px-1 max-sm:px-0.5 rounded-md max-lg:translate-y-6 overflow-hidden max-sm:text-xs' => true,
            'bg-slate-100 hover:bg-slate-200 text-slate-600 border-slate-300' => $appointment->app_start_time < now()
            ])>

            @if (\Carbon\Carbon::parse($appointment->app_start_time)->diffInMinutes(\Carbon\Carbon::parse($appointment->app_end_time)) >= 30)
                <span class="font-bold">
                    {{ \Carbon\Carbon::parse($appointment->app_start_time)->format('G:i') }}
                </span>
                <span class="font-normal">
                    {{ $appointment->user->first_name }}
                </span>
            @endif

        </div>
    </a>

</div>