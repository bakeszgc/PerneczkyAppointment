<x-email-layout>
    @php
        $startTime = Carbon\Carbon::parse($appointment->app_start_time);
        $endTime = Carbon\Carbon::parse($appointment->app_end_time);

        $isNotifiableBarber = $appointment->barber_id === $notifiable?->barber?->id;

        switch ($isNotifiableBarber) {
            case true:
                $notifiableName = $notifiable->barber->getName();

                $cancelledByName = $cancelledBy == 'admin' ? __('mail.an_admin') : $cancelledBy->first_name;

                $possessive = __('mail.their');
                
                $ctaText = __('mail.view_cancelled_booking');
                $url = route('appointments.show',$appointment);
            break;

            case false:
                $notifiableName = $notifiable->first_name;

                $cancelledByName = $cancelledBy == 'admin' ? strtolower(__('mail.an_admin')) : $cancelledBy->getName();

                $possessive = __('mail.your');

                $ctaText = __('mail.book_a_new_appointment');
                $url = route('my-appointments.create.barber.service',[
                    'barber_id' => $appointment->barber_id,
                    'service_id' => $appointment->service_id
                ]);
            break;
        }
    @endphp

    <h1 class="mb-4">
        {{ __('mail.hi') . ' ' . $notifiableName }},
    </h1>

    <p class="mb-8">
        {{ __('mail.unfortunately') }}
        
        @if ($cancelledBy == 'admin')
            {{ strtolower(__('mail.an_admin')) }}
        @else
            {{ $cancelledByName }}
        @endif
        
        {{ __('mail.has_cancelled_one_of') . ' ' . $possessive . ' ' .  __('mail.upcoming_appointments') . ' ' . __('mail.details_of_bookings') }}
    </p>

    <table class="mb-8">
        <thead>
            <tr>
                <th></th>
                <th>{{ __('mail.details') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ __('mail.date') }}</td>
                <td>{{ $startTime->format('Y-m-d') }}</td>
            </tr>

            <tr>
                <td>{{ __('mail.time') }}</td>
                <td>{{ $startTime->format('G:i') . ' - ' . $endTime->format('G:i') }}</td>
            </tr>

            <tr>
                <td>{{ __('mail.service') }}</td>
                <td>{{ $appointment->service->getName() }}</td>
            </tr>

            <tr>
                <td>{{ __('mail.price') }}</td>
                <td>{{ number_format($appointment->price,thousands_separator:' ') }} HUF</td>
            </tr>

            
            <tr>
                @if (!$isNotifiableBarber)
                    <td>{{ __('mail.barber') }}</td>
                    <td>{{ $appointment->barber->getName() }}</td>
                @else
                    <td>{{ __('mail.customer') }}</td>
                    <td>{{ $appointment->user->getFullName()}}</td>
                @endif
            </tr>

            <tr>
                <td>{{ __('mail.comment') }}</td>
                <td @class(['italic' => $appointment->comment == ''])>
                    {{ $appointment->getComment() }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="text-center mb-8">
        <a href="{{ $url }}" id="ctaButton" target="_blank">
            {{ $ctaText }}
        </a>
    </div>

    @if (!$isNotifiableBarber)
        <p class="mb-8">
            {{ __('mail.book_new') . ' ' . __('mail.booking_stored_p_3a') }}
            
            <a href="mailto:{{ env('COMPANY_MAIL') }}" class="link">{{ env('COMPANY_MAIL') }}</a>

            {{ __('mail.booking_stored_p_3b') }}

            <a href="tel:{{ str_replace(' ','',env('COMPANY_PHONE')) }}" class="link">{{ env('COMPANY_PHONE') }}</a>{{ __('mail.booking_stored_p_3c') }}
        </p>
    @else
        <p class="mb-8">
            {{ __('mail.you_can_view') . ' ' . $cancelledByName . __('mail.s1s_booking') . ' ' . __('mail.reschedule') }} 
        </p>
    @endif

    <hr class="mb-8" />

    <p id="linkTrouble">
        {{ __('mail.url_error_p_1') . $ctaText . __('mail.url_error_p_2') }}
        
        <a href="{{ $url }}" class="link word-break">{{ $url }}</a>
    </p>
</x-email-layout>