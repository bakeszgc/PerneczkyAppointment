<x-email-layout>
    @php
        $startTime = Carbon\Carbon::parse($appointment->app_start_time);
        $endTime = Carbon\Carbon::parse($appointment->app_end_time);

        $cancelledByClass = is_string($cancelledBy) ? 'Admin' : get_class($cancelledBy);

        switch ($cancelledByClass) {
            case 'App\Models\User':
                $notifiableName = $notifiable->barber->getName();
                $cancelledByName = $cancelledBy->first_name;

                $ctaText = "View Cancelled Booking";
                $url = route('appointments.show',$appointment);
            break;

            case 'App\Models\Barber':
            case 'Admin':
                $notifiableName = $notifiable->first_name;
                $cancelledByName = is_string($cancelledBy) ? 'an admin' : $cancelledBy->getName();

                $ctaText = 'Book a New Appointment';
                $url = route('my-appointments.create.barber.service',[
                    'barber_id' => $appointment->barber_id,
                    'service_id' => $appointment->service_id
                ]);
        }
    @endphp

    <h1 class="mb-4">Hi {{ $notifiableName }},</h1>

    <p class="mb-8">Unfortunately, {{ $cancelledByName }} has cancelled one of {{ !is_string($cancelledBy) && get_class($cancelledBy) == 'App\Models\User' ? 'their' : 'your' }} upcoming appointments. You can see the details of this booking below:</p>

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
                <td>{{ $appointment->service->name }}</td>
            </tr>

            <tr>
                <td>Price</td>
                <td>{{ number_format($appointment->price,thousands_separator:' ') }} HUF</td>
            </tr>

            
            <tr>
                @if ($cancelledByClass != 'App\Models\User')
                    <td>Barber</td>
                    <td>{{ $appointment->barber->getName() }}</td>
                @else
                    <td>Customer</td>
                    <td>{{ $appointment->user->first_name . ' ' . $appointment->user->last_name}}</td>
                @endif
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
        <a href="{{ $url }}" id="ctaButton" target="_blank">{{ $ctaText }}</a>
    </div>

    @if ($cancelledByClass != 'App\Models\User')
        <p class="mb-8">Don't forget to book another one by clicking on the button above. If you have any questions, need to reschedule, or require assistance, feel free to contact us at <a href="mailto:info@perneczkybarbershop.hu" class="link">info@perneczkybarbershop.hu</a> or call us at <a href="tel:+36704056079" class="link">+36 70 405 6079</a>.</p>
    @else
        <p class="mb-8">You can view {{ $cancelledByName }}'s booking by clicking on the button above. Try contacting your client about rescheduling their appointment.</p>
    @endif

    <hr class="mb-8" />

    <p id="linkTrouble">
        If you're having trouble clicking the "{{ $ctaText }}" button, copy and paste the URL below into your web browser: <a href="{{ $url }}" class="link word-break">{{ $url }}</a>
    </p>
</x-email-layout>