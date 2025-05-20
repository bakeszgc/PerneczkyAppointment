<x-card {{$attributes->merge(['class' => ' transition-all'])}}>
    <div @class(['flex justify-between' => true, 'text-slate-500' => $appointment->deleted_at])>
        <div>
            <h2 class="font-bold text-2xl max-sm:text-lg mb-1 flex items-center gap-2">
                <a href="{{ $access === 'barber' ? route('appointments.show',['appointment' => $appointment]) : route('my-appointments.show',['my_appointment' => $appointment]) }}"
                @class(['line-through' => $appointment->deleted_at])>
                    {{$appointment->user->first_name . " " . $appointment->user->last_name}} #{{$appointment->id}}
                </a>
                @if ($appointment->deleted_at)
                    <span class=" font-medium text-lg">Cancelled</span>
                @endif
            </h2>
            <h3 class="font-medium text-lg max-sm:text-sm mb-1">
                {{$appointment->service->name}}
                •
                {{number_format($appointment->price,thousands_separator:' ')}} Ft
            </h3>
            <p class="font-medium text-base max-sm:text-sm text-slate-500">
                Barber: {{$appointment->barber->display_name ?? $appointment->barber->user->first_name}}
            </p>
            <div>
                {{$slot}}
            </div>
        </div>
        <div class="text-right">
            <h2 class="font-bold text-2xl max-sm:text-lg mb-1">
                @if (Carbon\Carbon::parse($appointment->app_start_time)->isToday())
                    Today {{Carbon\Carbon::parse($appointment->app_start_time)->format('G:i')}}
                @elseif (Carbon\Carbon::parse($appointment->app_start_time)->isTomorrow())
                    Tomorrow {{Carbon\Carbon::parse($appointment->app_start_time)->format('G:i')}}
                @elseif (Carbon\Carbon::parse($appointment->app_start_time)->isYesterday())
                    Yesterday
                @else
                    {{Carbon\Carbon::parse($appointment->app_start_time)->format('Y.m.d. G:i')}}
                @endif
            </h2>
            <h3 class="font-medium text-lg max-sm:text-sm">
                Duration: {{$appointment->getDuration()}} minutes
            </h3>
        </div>
    </div>

    <div class="flex gap-2 mt-4">

        <x-link-button :link="$access === 'barber' ? route('appointments.show',['appointment' =>$appointment]) : route('my-appointments.show',['my_appointment' => $appointment])" role="show">
            Details
        </x-link-button>
        
        @if ($appointment->app_start_time >= now('Europe/Budapest') && !$appointment->deleted_at)
            @if ($access === 'barber')
                <x-link-button :link="route('appointments.edit',['appointment' => $appointment])" role="edit">
                    Edit
                </x-link-button>
            @endif

            <form action="{{$access === 'barber' ? route('appointments.destroy',['appointment' => $appointment]) : route('my-appointments.destroy',['my_appointment' => $appointment])}}" method="POST">
                @csrf
                @method('DELETE')
                <x-button role="destroy">
                    Cancel
                </x-button>
            </form>
        @endif
        
    </div>
</x-card>