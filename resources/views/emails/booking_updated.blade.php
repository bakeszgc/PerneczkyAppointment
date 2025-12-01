<x-email-layout>

    @php
        $oldStartTime = Carbon\Carbon::parse($oldAppointment['app_start_time']);
        $newStartTime = Carbon\Carbon::parse($newAppointment->app_start_time);

        $oldEndTime = Carbon\Carbon::parse($oldAppointment['app_end_time']);
        $newEndTime = Carbon\Carbon::parse($newAppointment->app_end_time);

        if ($updatedBy === 'admin') {
            $updatedByName = 'An admin has';
        } else {
            switch(get_class($updatedBy)){
                case 'App\Models\User':
                    $updatedByName = 'You have';
                break;

                case 'App\Models\Barber':
                    $updatedByName = $updatedBy->getName() . ' has';
                break;
            }
        }
    @endphp    

    <h1 class="mb-4">
        {{ __('mail.hi') . " " . $notifiable->first_name }},
    </h1>

    <p class="mb-8">
        @if ($updatedBy === 'admin')
            {{ __('mail.an_admin') . " " . __('mail.s1_updated') }}
        @else
            {{ get_class($updatedBy) == 'App\Models\Barber' ? ($newAppointment->barber->getName() . ' ' . __('mail.s1_updated')) : __('mail.you_updated') }}
        @endif
        {{ __('mail.changed_highlighted') }}
    </p>

    <table class="mb-8">
        <thead>
            <tr>
                <th></th>
                <th>{{ __('mail.old_details') }}</th>
                <th>{{ __('mail.new_details') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td @class(['changed' => $oldStartTime->format('Y-m-d') != $newStartTime->format('Y-m-d')])>
                    {{ __('mail.date') }}
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
                    {{ __('mail.time') }}
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
                    {{ __('mail.service') }}
                </td>
                <td @class(['changed' => $oldAppointment['service_id'] != $newAppointment->service_id])>
                    {{ App\Models\Service::find($oldAppointment['service_id'])->getName() }}
                </td>
                <td @class(['changed' => $oldAppointment['service_id'] != $newAppointment->service_id])>
                    {{ $newAppointment->service->getName() }}
                </td>
            </tr>

            <tr>
                <td @class(['changed' => $oldAppointment['price'] != $newAppointment->price])>
                    {{ __('mail.price') }}
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
                    {{ __('mail.barber') }}
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
                    {{ __('mail.comment') }}
                </td>

                <td @class(['italic' => $oldAppointment['comment'] == '', 'changed' => $oldAppointment['comment'] != $newAppointment->comment])>
                    {{ $oldAppointment['comment'] == '' ? __('appointments.no_comment') : $oldAppointment['comment']}}
                </td>

                <td @class(['italic' => $newAppointment->comment == '', 'changed' => $oldAppointment['comment'] != $newAppointment->comment])>
                    {{ $newAppointment->getComment() }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="text-center mb-8">
        <a href="{{ route('my-appointments.show',$newAppointment) }}" id="ctaButton" target="_blank">
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
        {{ __('mail.url_error_p_1') . __('mail.view_your_appointment') . __('mail.url_error_p_2') }}
        
        <a href="{{ route('my-appointments.show',$newAppointment) }}" class="link word-break">{{ route('my-appointments.show',$newAppointment) }}</a>
    </p>

</x-email-layout>