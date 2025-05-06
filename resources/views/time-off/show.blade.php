<x-user-layout title="My Time Off - " currentView="barber">
    <x-breadcrumbs :links="[
        'Bookings' => route('appointments.index'),
        'My Time Off' => ''
    ]"/>

    <div class="flex justify-between items-end mb-4">
        <x-headline>{{ $appointment->barber->display_name ?? $appointment->barber->user->first_name}}'s Time Off</x-headline>
        <x-link-button :link="route('time-off.create')" role="createMain">Add&nbsp;New</x-link-button>
    </div>

    <x-card>
        <div @class(['flex justify-between' => true, 'text-slate-500' => $appointment->deleted_at])>
            <div>
                <h2 class="font-bold text-2xl max-sm:text-lg mb-1 flex items-center gap-2">
                    <a href="{{ route('time-off.show',$appointment) }}"
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
                        Today {{ $appointment->getDuration() < 600 ? Carbon\Carbon::parse($appointment->app_start_time)->format('G:i') : ''}}
                    @elseif (Carbon\Carbon::parse($appointment->app_start_time)->isTomorrow())
                        Tomorrow {{ $appointment->getDuration() < 600 ? Carbon\Carbon::parse($appointment->app_start_time)->format('G:i') : ''}}
                    @else
                        {{$appointment->getDuration() < 600 ? Carbon\Carbon::parse($appointment->app_start_time)->format('Y.m.d. G:i') : Carbon\Carbon::parse($appointment->app_start_time)->format('Y.m.d.')}}
                    @endif
                </h2>
                <h3 class="font-medium text-lg max-sm:text-sm">
                    Duration: {{$appointment->getDuration() >= 600 ? 'Full day' : $appointment->getDuration() . ' minutes'}} 
                </h3>
            </div>
        </div>
        <div class="flex gap-2 mt-4">        
            @if ($appointment->app_start_time >= now('Europe/Budapest') && !$appointment->deleted_at)
                <x-link-button :link="route('time-off.edit',$appointment)" role="edit">
                    Edit
                </x-link-button>

                <form action="{{route('time-off.destroy',$appointment) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <x-button role="destroy">
                        Cancel
                    </x-button>
                </form>
            @endif
        
        </div>
    </x-card>

</x-user-layout>