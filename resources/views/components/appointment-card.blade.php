<x-card {{$attributes->merge(['class' => ''])}}>
    <div class="flex justify-between">
        <div>
            <h2 class="font-bold text-2xl mb-1">
                {{$appointment->user->first_name . " " . $appointment->user->last_name}} #{{$appointment->id}}
            </h2>
            <h3 class="font-medium text-lg mb-1">
                {{$appointment->service->name}}
                •
                {{number_format($appointment->price,thousands_separator:' ')}} Ft
            </h3>
            <p class="font-medium text-base text-slate-500">
                Barber: {{$appointment->barber->display_name ?? $appointment->barber->user->first_name}}
            </p>
            <div>
                {{$slot}}
            </div>
        </div>
        <div class=" text-right">
            <h2 class="font-bold text-2xl mb-1">{{Carbon\Carbon::parse($appointment->app_start_time)->format('Y.m.d. G:i')}}</h2>
            <h3 class="font-medium text-lg">
                Duration: {{$appointment->service->duration}} minutes
            </h3>
        </div>
    </div>

    <div class="flex gap-2 mt-4">
        <x-link-button :link="route('appointments.show',['appointment' => $appointment])" role="show">
            Details
        </x-link-button>
        
        @if ($appointment->app_start_time >= now())
            @if ($editable)
            <x-link-button :link="route('appointments.edit',['appointment' => $appointment])" role="edit">
                Edit
            </x-link-button>
            @endif
            

            <form action="{{route('appointments.destroy',$appointment)}}" method="POST">
                @csrf
                @method('DELETE')
                <x-button :link="route('appointments.destroy',['appointment' => $appointment])" role="destroy">
                    Cancel
                </x-button>
            </form>
        @endif
        
    </div>
</x-card>