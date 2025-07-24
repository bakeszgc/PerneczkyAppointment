<x-card {{ $attributes->merge(['class' => '']) }}>
    <div class="relative">
        
        <div class="flex text-center mb-4">
            <div class="w-1/8"></div>
            @for ($i = 1; $i<=7; $i++)
                <div class="flex items-center justify-center gap-1 max-lg:flex-col w-1/8">
                    <span class="text-slate-500">
                        {{ date('D', strtotime("Sunday + {$i} days")) }}
                    </span>
                    <span @class([
                            'font-bold rounded-full p-1 transition-all' => true,
                            'bg-blue-600 text-white hover:bg-blue-800' => today()->format('D') == date('D', strtotime("Sunday + {$i} days")),
                            'hover:bg-slate-300' => today()->format('D') != date('D', strtotime("Sunday + {$i} days"))
                        ])>
                        {{ today()->format('D') == date('D', strtotime("Sunday + {$i} days")) ? today()->format('d') : today()->addDays($i - today()->format('N'))->format('d') }}
                    </span>
                </div>
            @endfor
        </div>
        <div>
            @for ($i = 10; $i<=21; $i++)
                <div class="text-slate-500 border-slate-300 border-t mb-8">
                    {{ $i }}:00
                </div>
            @endfor
        </div>
        
        @foreach ($calAppointments as $appointment)
            @isset($barber)
                <x-calendar-event :appointment="$appointment" :barber="$barber" />
            @else
                <x-calendar-event :appointment="$appointment" />
            @endisset
        @endforeach
        
        @if (now()->format('G') >= 10 && now()->format('G') <= 21)
            <div style="position: absolute; width: 100%; height: 1px; background-color: blue; top: {{ 53/60 * (now()->format('G') * 60 + now()->format('i')) - 486 }}px;">
            </div>
        @endif
        
    </div>
</x-card>