<div style="{{ "position: absolute; width: 12.5%;
top: " . 53/60 * (\Carbon\Carbon::parse($appointment->app_start_time)->format('G')*60 + \Carbon\Carbon::parse($appointment->app_start_time)->format('i')) -486 .
"px; left: calc(" . (\Carbon\Carbon::parse($appointment->app_start_time)->format('N')) * 12.5 . "%);
height: calc(" . $appointment->getDuration() / 60 * 53 .
"px); padding: 0.125rem;" }}">

    <a href="{{ $appointment->service_id !== 1 ? route('appointments.show',$appointment) : route('time-off.show',$appointment) }}">
        <div @class([
            'bg-blue-100 hover:bg-blue-200 border border-blue-300 transition-all text-blue-600 h-full px-1 max-sm:px-0.5 rounded-md max-lg:translate-y-6 overflow-hidden max-sm:text-xs',
            'border-dashed bg-green-100 hover:bg-green-200 border-green-600 text-green-600' => $appointment->service_id == 1,
            'bg-slate-100 hover:bg-slate-200 text-slate-600 border-slate-300' => $appointment->app_start_time < now(),
            ])>

            @if ($appointment->getDuration() >= 30)
                <span class="font-bold">
                    {{ \Carbon\Carbon::parse($appointment->app_start_time)->format('G:i') }}
                </span>
                <span class="font-normal">
                    {{ $appointment->service_id !== 1 ? $appointment->user->first_name : 'TIME OFF'}}
                </span>
            @endif

        </div>
    </a>

</div>