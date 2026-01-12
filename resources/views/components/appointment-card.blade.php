@php
    use Carbon\Carbon;

    $detailsLink = '';
    $editLink = '';
    $cancelLink = '';

    switch($access) {
        case 'admin':
            $detailsLink = route('bookings.show',['booking' => $appointment]);
            $editLink = route('bookings.edit',['booking' => $appointment]);
            $cancelLink = route('bookings.destroy',['booking' => $appointment]);
            $rebookLink = route('bookings.create.date',[
                'user_id' => $appointment->user_id,
                'service_id' => $appointment->service_id,
                'barber_id' => $appointment->barber_id,
                'comment' => $appointment->comment
            ]);
            break;
        case 'barber':
            $detailsLink = route('appointments.show',['appointment' =>$appointment]);
            $editLink = route('appointments.edit',['appointment' => $appointment]);
            $cancelLink = route('appointments.destroy',['appointment' => $appointment]);
            $rebookLink = route('appointments.create.date',[
                'user_id' => $appointment->user_id,
                'service_id' => $appointment->service_id,
                'comment' => $appointment->comment
            ]);
            break;
        default:
            $detailsLink = route('my-appointments.show',['my_appointment' => $appointment]);
            $cancelLink = route('my-appointments.destroy',['my_appointment' => $appointment]);
            $editLink = route('my-appointments.edit',['my_appointment' => $appointment]);
            $rebookLink = route('my-appointments.create.date',[
                'service_id' => $appointment->service_id,
                'barber_id' => $appointment->barber_id,
                'comment' => $appointment->comment
            ]);
    }
@endphp

<x-card {{$attributes->merge(['class' => ' transition-all'])}}>
    <div @class(['flex justify-between mb-2 md:mb-4' => true, 'text-slate-500' => $appointment->deleted_at || $appointment->app_start_time < now()])>
        <div>
            <h2 @class(['font-bold text-2xl max-sm:text-lg mb-1 flex items-center gap-2' => true, 'text-blue-600 hover:text-blue-800' => $appointment->app_start_time >= now() && !$appointment->deleted_at ])>
                <a href="{{ $detailsLink }}"
                @class(['line-through' => $appointment->deleted_at])>
                    {{$appointment->user->getFullName() . ' #' .$appointment->id}}
                </a>
                <span class="font-medium text-lg max-sm:hidden">{{ $appointment->isDeleted() }}</span>
            </h2>

            <h3 class="font-medium text-lg max-sm:text-sm mb-1">
                @if ($access == 'admin')
                    <a href="{{ route('services.show',$appointment->service) }}" class="text-blue-700 hover:underline">
                @endif
                    {{$appointment->service->getName() .' '. $appointment->service->isDeleted() }}
                @if ($access == 'admin')
                    </a>
                @endif
                
                <span class="max-md:hidden">•</span>
                <br class="md:hidden">
                
                {{number_format($appointment->price,thousands_separator:' ')}} HUF
            </h3>

            <p class="font-medium text-base max-sm:text-sm text-slate-500">
                {{ __('appointments.barber') }}: 
                @if ($access == 'admin')
                    <a href="{{ route('barbers.show',$appointment->barber) }}" class="text-blue-700 hover:underline">
                @endif

                {{$appointment->barber->getName() .' '. $appointment->barber->isDeleted() }}

                @if ($access == 'admin')
                    </a>
                @endif
            </p>
            
            <div>
                {{$slot}}
            </div>
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
                    {{Carbon::parse($appointment->app_start_time)->format('Y-m-d')}}
                @endif
            </h2>
            <h3 class="font-medium text-lg max-sm:text-sm">
                {{ $appointment->getDuration() >= 600 ? __('appointments.full_day') : Carbon::parse($appointment->app_start_time)->format('G:i') . ' - ' . Carbon::parse($appointment->app_end_time)->format('G:i') }}
            </h3>
            <h4 class="text-base max-sm:text-sm">
                {{ $appointment->getDuration() . ' ' .__('home.minutes')}}
            </h4>
        </div>
    </div>

    <div class="flex gap-2">
        
        @if($showDetails)
            <x-link-button :link="$detailsLink" role="show" :title="__('appointments.details_tooltip_' . ($access != 'user' ? 2 : 1))">
                <span class="max-sm:hidden">
                    {{ __('appointments.details') }}
                </span>
            </x-link-button>
        @endif
        
        @if (
            ($access == 'user' && $appointment->app_start_time >= now('Europe/Budapest')->addHours(6)) ||
            ($access != 'user' && $appointment->app_start_time >= now('Europe/Budapest')) &&
            !$appointment->deleted_at
        )
            <x-link-button :link="$editLink" role="edit" :title="__('appointments.edit_tooltip_' . ($access != 'user' ? 2 : 1))">
                <span class="max-sm:hidden">
                    {{ __('appointments.edit') }}
                </span>
            </x-link-button>

            <form action="{{ $cancelLink }}" method="POST">
                @csrf
                @method('DELETE')
                <x-button role="destroy" :title="__('appointments.cancel_tooltip_' . ($access != 'user' ? 2 : 1))">
                    <span class="max-sm:hidden">
                        {{ __('appointments.cancel') }}
                    </span>
                </x-button>
            </form>
        @endif

        @if (($appointment->app_start_time < now() || $appointment->deleted_at) && !$appointment->user->deleted_at && !$appointment->service->deleted_at && !$appointment->barber->deleted_at && $appointment->user->hasEmail())
            <x-link-button :link="$rebookLink" role="restore" :title="__('appointments.rebook_tooltip_' . ($access != 'user' ? 2 : 1))">
                <span class="max-sm:hidden">
                    {{ __('appointments.rebook') }}
                </span>
            </x-link-button>
        @endif
        
        
    </div>
</x-card>