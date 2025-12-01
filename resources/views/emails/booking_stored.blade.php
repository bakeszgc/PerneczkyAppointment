<x-email-layout>
    @php
        $startTime = Carbon\Carbon::parse($appointment->app_start_time);
        $endTime = Carbon\Carbon::parse($appointment->app_end_time);
    @endphp

    <h1 class="mb-4">{{ __('mail.hi') . ' ' . $notifiable->first_name }},</h1>

    <p class="mb-8">
        {{ __('mail.booking_stored_p_1') }}
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
                <td>{{ $startTime->format('G:i') }} - {{ $endTime->format('G:i') }}</td>
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
                <td>{{ __('mail.barber') }}</td>
                <td>{{ $appointment->barber->getName() }}</td>
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
        <a href="{{ route('my-appointments.show',$appointment) }}" id="ctaButton" target="_blank">
            {{ __('mail.view_your_appointment') }}
        </a>
    </div>

    <p class="mb-4">
        {{ __('mail.booking_stored_p_2') }}
    </p>

    <p class="mb-8">
        {{ __('mail.booking_stored_p_3a') }}
        <a href="mailto:{{ env('COMPANY_MAIL') }}" class="link">{{ env('COMPANY_MAIL') }}</a>
        {{ __('mail.booking_stored_p_3b') }}
        <a href="tel:{{ str_replace(' ','',env('COMPANY_PHONE')) }}" class="link">{{ env('COMPANY_PHONE') }}</a>{{ __('mail.booking_stored_p_3c') }}
    </p>

    <hr class="mb-8" />

    <p id="linkTrouble">
        If you're having trouble clicking the "View your appointment" button, copy and paste the URL below into your web browser: <a href="{{ route('my-appointments.show',$appointment) }}" class="link word-break">{{ route('my-appointments.show',$appointment) }}</a>
    </p>
</x-email-layout>