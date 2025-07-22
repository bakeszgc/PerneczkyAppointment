@php
    use Carbon\Carbon;
@endphp

<div style="{{ "position: absolute; width: 12.5%;
top: " . 53/60 * (Carbon::parse($appointment->app_start_time)->format('G')*60 + Carbon::parse($appointment->app_start_time)->format('i')) -486 .
"px; left: calc(" . (Carbon::parse($appointment->app_start_time)->format('N')) * 12.5 . "%);
height: calc(" . $appointment->getDuration() / 60 * 53 .
"px); padding: 0.125rem;" }}">

    @php
        $showLink = '';

        if ($appointment->service_id !== 1) {
            if (isset($barber)) {
                $showLink = route('bookings.show',['barber' => $barber, 'booking' => $appointment]);
            } else {
                $showLink = route('appointments.show',$appointment);
            }
        } else {
            $showLink = route('time-offs.show',$appointment);
        }

    @endphp

    <a href="{{ $showLink }}">
        <div @class([
            'border border-l-4 transition-all h-full px-1 max-sm:px-0.5 rounded-md max-lg:translate-y-6 overflow-hidden max-sm:text-xs',
            'bg-slate-100 hover:bg-slate-200 text-slate-600 border-slate-300' => $appointment->app_start_time < now(),
            'bg-green-100 hover:bg-green-200 border-green-400 text-green-600' => $appointment->app_start_time >= now() && $appointment->service_id == 1,
            'bg-blue-100 hover:bg-blue-200 border-blue-300 text-blue-600' => $appointment->app_start_time >= now() && $appointment->service_id != 1
        ])>

            @if ($appointment->getDuration() >= 30)
                <span class="font-bold">
                    {{ Carbon::parse($appointment->app_start_time)->format('G:i') }}
                </span>
                <span class="font-normal">
                    {{ $appointment->service_id !== 1 ? $appointment->user->first_name : 'TIME OFF'}}
                </span>
            @endif

        </div>
    </a>

</div>