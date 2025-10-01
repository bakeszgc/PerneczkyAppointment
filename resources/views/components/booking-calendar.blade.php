@php
    use Carbon\Carbon;
@endphp

<div class="w-full">
    <div class="w-full text-center mb-4 text-xl max-md:text-lg font-bold">
        <p>{{ $firstDaytOfMonth->format('F Y') }}</p>
    </div>
    <div class="grid grid-cols-7 gap-4 *:text-center">
        <p>Mo</p>
        <p>Tu</p>
        <p>We</p>
        <p>Th</p>
        <p>Fr</p>
        <p>Sa</p>
        <p>Su</p>
    </div>
    <div class="grid grid-cols-7 gap-4">
        @for ($i=0; $i < $firstDaytOfMonth->format('w')+6 % 7; $i++)
            <p></p>
        @endfor
        @for ($i = 1; $i<= $firstDaytOfMonth->month(intval($firstDaytOfMonth->format('m')))->daysInMonth(); $i++)
            <div>
                @php
                    $date = new Carbon($firstDaytOfMonth->format('Y-m-') . $i);
                    //$isEnabled = $date >= today() && $date <= today()->addWeeks(2);
                    $isEnabled = true;
                    if (!array_key_exists($date->format('Y-m-d'),$availableSlotsByDate)) {
                        $isEnabled = false;
                    }
                @endphp
                
                <label {{ $isEnabled ? 'for=day_' . $firstDaytOfMonth->format('m').$i : '' }} @class([
                    'rounded-full w-8 h-8 m-auto flex items-center
                    has-[input:checked]:bg-[#0018d5] has-[input:checked]:shadow-2xl has-[input:checked]:text-white',
                    'hover:text-white hover:bg-blue-400 transition-all cursor-pointer' => $isEnabled,
                    'text-slate-300' => !$isEnabled
                    ])>
                    <p class="m-auto w-fit">
                        {{ $i }}
                    </p>
                    @if ($isEnabled)
                        <input type="radio" name="day" id="day_{{ $firstDaytOfMonth->format('m').$i }}" value="{{ $firstDaytOfMonth->format('Y-m'). '-' .($i < 10 ? '0' : '') . $i }}" class="hidden" x-model="selectedDate">
                    @endif

                </label>
            </div>
        @endfor
    </div>
</div>