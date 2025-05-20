<x-card {{$attributes->merge(['class' => ''])}}>
    <div @class(['flex justify-between' => true, 'text-slate-500' => $appointment->deleted_at || $appointment->app_start_time < now()])>
        <div>
            <h2 class="font-bold text-2xl max-sm:text-lg mb-1 flex items-center gap-2">
                <a href="{{ route('time-offs.show',$appointment) }}"
                @class(['line-through' => $appointment->deleted_at])>
                    {{$appointment->user->first_name . " " . $appointment->user->last_name}} #{{$appointment->id}}
                </a>
                @if ($appointment->deleted_at)
                    <span class=" font-medium text-lg">Cancelled</span>
                @endif
            </h2>
            <h3 class="font-medium text-lg max-sm:text-sm mb-1">
                TIME OFF
            </h3>
        </div>
        <div class="text-right">
            <h2 class="font-bold text-2xl max-sm:text-lg mb-1">
                @if (Carbon\Carbon::parse($appointment->app_start_time)->isToday())
                    Today
                @elseif (Carbon\Carbon::parse($appointment->app_start_time)->isTomorrow())
                    Tomorrow
                @elseif (Carbon\Carbon::parse($appointment->app_start_time)->isYesterday())
                    Yesterday
                @else
                    {{ Carbon\Carbon::parse($appointment->app_start_time)->format('Y.m.d.') }}
                @endif
            </h2>
            <h3 class="font-medium text-lg max-sm:text-sm">
                {{ $appointment->getDuration() >= 600 ? 'Full day' : Carbon\Carbon::parse($appointment->app_start_time)->format('G:i') . ' - ' . Carbon\Carbon::parse($appointment->app_end_time)->format('G:i') . ' (' . $appointment->getDuration() . ' minutes)'}}
            </h3>
        </div>
    </div>
    <div class="flex gap-2 mt-4">
        @if ($showDetails)
            <x-link-button :link="route('time-offs.show',$appointment)" role="show">
                Details
            </x-link-button>
        @endif
        @if ($appointment->app_start_time >= now('Europe/Budapest') && !$appointment->deleted_at)
            <x-link-button :link="route('time-offs.edit',$appointment)" role="edit">
                Edit
            </x-link-button>
            <form action="{{route('time-offs.destroy',$appointment) }}" method="POST">
                @csrf
                @method('DELETE')
                <x-button role="destroy">
                    Cancel
                </x-button>
            </form>
        @endif
    
    </div>
</x-card>