<x-email-layout>

    @php
        $oldStartTime = Carbon\Carbon::parse($oldAppointment['app_start_time']);
        $newStartTime = Carbon\Carbon::parse($newAppointment->app_start_time);

        $oldEndTime = Carbon\Carbon::parse($oldAppointment['app_end_time']);
        $newEndTime = Carbon\Carbon::parse($newAppointment->app_end_time);
    @endphp    

    <h1 class="mb-4">Hi {{ $notifiable->first_name }},</h1>

    <p class="mb-8">{{ $updatedBy === 'admin' ? 'An admin' : $updatedBy->getName() }} has modified some details of your upcoming appointment. You can see the changes highlighted below:</p>

    <table class="mb-8">
        <thead>
            <tr>
                <th></th>
                <th>Old details</th>
                <th>New details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td @class(['changed' => $oldStartTime->format('Y-m-d') != $newStartTime->format('Y-m-d')])>
                    Date
                </td>
                <td @class(['changed' => $oldStartTime->format('Y-m-d') != $newStartTime->format('Y-m-d')])>
                    {{ $oldStartTime->format('Y-m-d') }}
                </td>
                <td @class(['changed' => $oldStartTime->format('Y-m-d') != $newStartTime->format('Y-m-d')])>
                    {{ $newStartTime->format('Y-m-d') }}
                </td>
            </tr>

            <tr>
                <td @class(['changed' => $oldStartTime->format('G:i') != $newStartTime->format('G:i') || $oldEndTime->format('G:i') != $newEndTime->format('G:i')])>
                    Time
                </td>
                <td @class(['changed' => $oldStartTime->format('G:i') != $newStartTime->format('G:i') || $oldEndTime->format('G:i') != $newEndTime->format('G:i')])>
                    {{ $oldStartTime->format('G:i') }} - {{ $oldEndTime->format('G:i') }}
                </td>
                <td @class(['changed' => $oldStartTime->format('G:i') != $newStartTime->format('G:i') || $oldEndTime->format('G:i') != $newEndTime->format('G:i')])>
                    {{ $newStartTime->format('G:i') }} - {{ $newEndTime->format('G:i') }}
                </td>
            </tr>

            <tr>
                <td @class(['changed' => $oldAppointment['service_id'] != $newAppointment->service_id])>
                    Service
                </td>
                <td @class(['changed' => $oldAppointment['service_id'] != $newAppointment->service_id])>
                    {{ App\Models\Service::find($oldAppointment['service_id'])->name }}
                </td>
                <td @class(['changed' => $oldAppointment['service_id'] != $newAppointment->service_id])>
                    {{ $newAppointment->service->name }}
                </td>
            </tr>

            <tr>
                <td @class(['changed' => $oldAppointment['price'] != $newAppointment->price])>
                    Price
                </td>
                <td @class(['changed' => $oldAppointment['price'] != $newAppointment->price])>
                    {{ number_format($oldAppointment['price'],thousands_separator:' ') }} HUF
                </td>
                <td @class(['changed' => $oldAppointment['price'] != $newAppointment->price])>
                    {{ number_format($newAppointment->price,thousands_separator:' ') }} HUF
                </td>
            </tr>
            
            <tr>
                <td @class(['changed' => $oldAppointment['barber_id'] != $newAppointment->barber_id])>
                    Barber
                </td>
                <td @class(['changed' => $oldAppointment['barber_id'] != $newAppointment->barber_id])>
                    {{ App\Models\Barber::find($oldAppointment['barber_id'])->getName() }}
                </td>
                <td @class(['changed' => $oldAppointment['barber_id'] != $newAppointment->barber_id])>
                    {{ $newAppointment->barber->getName() }}
                </td>
            </tr>

            <tr>
                <td @class(['changed' => $oldAppointment['comment'] != $newAppointment->comment])>
                    Comment
                </td>
                <td @class(['italic' => $oldAppointment['comment'] == '', 'changed' => $oldAppointment['comment'] != $newAppointment->comment])>
                    {{ $oldAppointment['comment'] == '' ? ('No comments from ' . $notifiable->first_name . '.') : $oldAppointment['comment']}}
                </td>
                <td @class(['italic' => $newAppointment->comment == '', 'changed' => $oldAppointment['comment'] != $newAppointment->comment])>
                    {{ $newAppointment->comment == '' ? ('No comments from ' . $notifiable->first_name . '.') : $newAppointment->comment}}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="text-center mb-8">
        <a href="{{ route('my-appointments.show',$newAppointment) }}" id="ctaButton" target="_blank">View your appointment</a>
    </div>            

    <p class="mb-4">Please make sure to arrive at least 5 minutes before your scheduled time to ensure a smooth experience. We accept both credit card and cash at our store. See you soon!</p>

    <p class="mb-8">If you have any questions, need to reschedule, or require assistance, feel free to contact us at <a href="mailto:info@perneczkybarbershop.hu" class="link">info@perneczkybarbershop.hu</a> or call us at <a href="tel:+36704056079" class="link">+36 70 405 6079</a>.</p>

    <hr class="mb-8" />

    <p id="linkTrouble">
        If you're having trouble clicking the "View your appointment" button, copy and paste the URL below into your web browser: <a href="{{ route('my-appointments.show',$newAppointment) }}" class="link word-break">{{ route('my-appointments.show',$newAppointment) }}</a>
    </p>

</x-email-layout>