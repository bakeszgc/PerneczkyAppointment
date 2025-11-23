@if ($appointment)
    <a href="{{ route('bookings.show',$appointment) }}" class="rounded-md border p-2 text-center transition-all hover:bg-[#0018d5] hover:text-white border-[#0018d5]">
@else
    <div class="rounded-md border p-2 text-center transition-all border-slate-300 bg-slate-100 text-slate-400">
@endif

    <h3 class="text-lg max-sm:text-sm font-semibold mb-1">{{ ucfirst($type) }} booking</h3>

@if ($appointment)
    <p class="text-xs">{{$appointment->service->getName() . " w/ " . $appointment->barber->getName() }}</p>
    <p class="text-xs">{{ Carbon\Carbon::parse($appointment->app_start_time)->format('Y-m-d G:i') }}</p>
    </a>
@else
    <p class="text-xs">This user does not have any bookings in the {{ $type == 'next' ? 'future' : 'past' }}</p>
    </div>
@endif