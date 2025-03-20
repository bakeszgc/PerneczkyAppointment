<div style="{{ "position: absolute; width: 109.42px;
top: " . 53/60 * \Carbon\Carbon::parse($appointment->app_start_time)->format('G')*60 +\Carbon\Carbon::parse($appointment->app_start_time)->format('i') -486 .
"px; left: " . \Carbon\Carbon::parse($appointment->app_start_time)->format('N') * 109.42-45.42 . "px;
height: " . \Carbon\Carbon::parse($appointment->app_start_time)->diffInMinutes(\Carbon\Carbon::parse($appointment->app_end_time)) / 60 * 53 .
"px;" }}">

    <a href="{{ route('appointments.show',$appointment) }}">
        <div @class([
            'bg-blue-100 hover:bg-blue-200 border border-blue-300 transition-all text-blue-600 h-full m-0.5 px-1 rounded-md' => true,
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