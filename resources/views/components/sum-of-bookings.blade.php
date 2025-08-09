<div {{ $attributes->merge(['class' => 'grid grid-cols-3']) }}>
    
    @foreach ($sumOfBookings as $bookingType => $sumOfBooking)
        <div class="even:border-x-2">
            @php
                $arguments = [
                    'barber' => $barber->id,
                    'customer' => $user->id
                ];

                if ($bookingType != 'cancelled') {
                    $arguments['time_window'] = $bookingType;
                } else {
                    $arguments['cancelled'] = 2;
                }
            @endphp
            <a href="{{ route('bookings.index',$arguments) }}" class="flex flex-col items-center hover:text-[#0018d5] transition-all">
                @switch($bookingType)
                    @case('upcoming')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061A1.125 1.125 0 0 1 3 16.811V8.69ZM12.75 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061a1.125 1.125 0 0 1-1.683-.977V8.69Z" />
                        </svg>
                        @break
                    @case('previous')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 16.811c0 .864-.933 1.406-1.683.977l-7.108-4.061a1.125 1.125 0 0 1 0-1.954l7.108-4.061A1.125 1.125 0 0 1 21 8.689v8.122ZM11.25 16.811c0 .864-.933 1.406-1.683.977l-7.108-4.061a1.125 1.125 0 0 1 0-1.954l7.108-4.061a1.125 1.125 0 0 1 1.683.977v8.122Z" />
                        </svg>
                    @break
                    @case('cancelled')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    @break
                @endswitch
                <h2 class="text-xl font-bold mb-2">{{ ucfirst($bookingType) }}</h2>
            </a>
            <div class="flex justify-between w-full px-8">
                <div class="flex-1">
                    <p>Bookings</p>
                    <p>Income</p>
                </div>
                <div class="text-right">
                    <p>{{ $sumOfBooking['count'] }}</p>
                    <p>{{ number_format($sumOfBooking['income'],thousands_separator:' ') }} HUF</p>
                </div>
            </div>
        </div>
    @endforeach
</div>