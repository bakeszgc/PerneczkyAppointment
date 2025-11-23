@php
    use Carbon\Carbon;

    switch ($access) {
        case 'barber':
            $showRoute = route('time-offs.show',$appointment);
            $editRoute = route('time-offs.edit',$appointment);
            $destroyRoute = route('time-offs.destroy',$appointment);
        break;

        case 'admin':
            $showRoute = route('admin-time-offs.show',$appointment);
            $editRoute = route('admin-time-offs.edit',$appointment);
            $destroyRoute = route('admin-time-offs.destroy',$appointment);
        break;
    }
@endphp

<x-card {{$attributes->merge(['class' => ''])}}>
    <div @class(['flex justify-between' => true, 'text-slate-500' => $appointment->deleted_at || $appointment->app_start_time < now()])>
        <div class="mb-4">
            <h2 @class(['font-bold text-2xl max-sm:text-lg mb-1 flex items-center gap-2' => true, 'text-green-600 hover:text-green-800' => $appointment->app_start_time >= now() && !$appointment->deleted_at ])>
                <a href="{{ $showRoute }}"
                @class(['line-through' => $appointment->deleted_at])>
                    {{$appointment->user->getFullName() . " #" . $appointment->id}}
                </a>
                <span class="font-medium text-lg">{{ $appointment->isDeleted() }}</span>
            </h2>

            @if ($access == 'admin')
                <p class="font-medium text-lg max-sm:text-sm">
                    {{ __('appointments.barber') }}: <a href="{{ route('barbers.show',$appointment->barber) }}" class="text-green-700 hover:underline">{{$appointment->barber->getName() }} {{ $appointment->barber->isDeleted() }} </a>
                </p>
            @endif
            
            <p class="font-medium text-lg max-sm:text-sm">
                {{ __('barber.timeoff_uc') }}
            </p>
        </div>

        <div class="text-right min-w-max">
            <h2 class="font-bold text-2xl max-sm:text-lg mb-1">
                @if (Carbon::parse($appointment->app_start_time)->isToday())
                    {{ __('appointments.today') }}
                @elseif (Carbon::parse($appointment->app_start_time)->isTomorrow())
                    {{ __('appointments.tomorrow') }}
                @elseif (Carbon::parse($appointment->app_start_time)->isYesterday())
                    {{ __('appointments.yesterday') }}
                @else
                    {{ Carbon::parse($appointment->app_start_time)->format('Y-m-d') }}
                @endif
            </h2>
            <h3 class="font-medium text-lg max-sm:text-sm">
                {{ Carbon::parse($appointment->app_start_time)->format('G:i') . ' - ' . Carbon::parse($appointment->app_end_time)->format('G:i') }}
            </h3>
            <h4 class="text-base max-sm:text-sm">
                {{ $appointment->getDuration() >= 600 ? __('appointments.full_day') : $appointment->getDuration() . ' ' . __('home.minutes') }}
            </h4>
        </div>
    </div>

    <div class="flex gap-2">
        @if ($showDetails)
            <x-link-button :link="$showRoute" role="show">
                <span class="max-sm:hidden">{{ __('appointments.details') }}</span>
            </x-link-button>
        @endif
        @if ($appointment->app_start_time >= now('Europe/Budapest') && !$appointment->deleted_at)
            <x-link-button :link="$editRoute" role="edit">
                <span class="max-sm:hidden">{{ __('appointments.edit') }}</span>
            </x-link-button>
            <form action="{{ $destroyRoute }}" method="POST">
                @csrf
                @method('DELETE')
                <x-button role="destroy">
                    <span class="max-sm:hidden">{{ __('appointments.cancel') }}</span>
                </x-button>
            </form>
        @endif
    
    </div>
</x-card>