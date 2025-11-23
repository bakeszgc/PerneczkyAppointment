@if ($appointment)
    <a href="{{ route('bookings.show',$appointment) }}" class="rounded-md border p-2 text-center transition-all hover:bg-[#0018d5] hover:text-white border-[#0018d5]">
@else
    <div class="rounded-md border p-2 text-center transition-all border-slate-300 bg-slate-100 text-slate-400">
@endif

    <h3 class="text-lg max-sm:text-sm font-semibold mb-1">{{ $type == 'latest' ? __('admin.latest_booking') : __('admin.next_booking') }}</h3>

@if ($appointment)
    <p class="text-xs">{{$appointment->service->getName() . " " . __('admin.with') ." " . $appointment->barber->getName() }}</p>
    <p class="text-xs">{{ Carbon\Carbon::parse($appointment->app_start_time)->format('Y-m-d G:i') }}</p>
    </a>
@else
    <p class="text-xs">
        {{ $type == 'next' ? __('admin.no_bookings_in_future') : __('admin.no_bookings_in_past') }}
    </p>
    </div>
@endif