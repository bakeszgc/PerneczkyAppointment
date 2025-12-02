<x-email-layout>
    @php
        $startTime = Carbon\Carbon::parse($appointment->app_start_time);
        $endTime = Carbon\Carbon::parse($appointment->app_end_time);
    @endphp

    <h1 class="mb-4">Hi {{ $notifiable->first_name }},</h1>

    <p class="mb-8">Reminder: You have an upcoming appointment today! Here are the details of your booking:</p>

    <table class="mb-8">
        <thead>
            <tr>
                <th></th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Date</td>
                <td>{{ $startTime->format('Y-m-d') }}</td>
            </tr>

            <tr>
                <td>Time</td>
                <td>{{ $startTime->format('G:i') }} - {{ $endTime->format('G:i') }}</td>
            </tr>

            <tr>
                <td>Service</td>
                <td>{{ $appointment->service->getName() }}</td>
            </tr>

            <tr>
                <td>Price</td>
                <td>{{ number_format($appointment->price,thousands_separator:' ') }} HUF</td>
            </tr>

            <tr>
                <td>Barber</td>
                <td>{{ $appointment->barber->getName() }}</td>
            </tr>

            <tr>
                <td>Comment</td>
                <td @class(['italic' => $appointment->comment == ''])>
                    {{ $appointment->comment == '' ? ('No comments from ' . $notifiable->first_name . '.') : $appointment->comment}}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="text-center mb-8">
        <a href="{{ route('my-appointments.show',$appointment) }}" id="ctaButton" target="_blank">View your appointment</a>
    </div>

    <p class="mb-4">Please make sure to arrive at least 5 minutes before your scheduled time to ensure a smooth experience. We accept both credit card and cash at our store. See you at {{ $startTime->format('G:i') }} today!</p>

    <p class="mb-8">If you have any questions, need to reschedule, or require assistance, feel free to contact us at <a href="mailto:{{ env('COMPANY_MAIL') }}" class="link">{{ env('COMPANY_MAIL') }}</a> or call us at <a href="tel:{{ str_replace(' ','',env('COMPANY_PHONE')) }}" class="link">{{ env('COMPANY_PHONE') }}</a>.</p>

    <hr class="mb-8" />

    <p id="linkTrouble">
        If you're having trouble clicking the "View your appointment" button, copy and paste the URL below into your web browser: <a href="{{ route('my-appointments.show',$appointment) }}" class="link word-break">{{ route('my-appointments.show',$appointment) }}</a>
    </p>
</x-email-layout>