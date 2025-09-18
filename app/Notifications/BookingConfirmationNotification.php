<?php

namespace App\Notifications;

use Carbon\Carbon;
use App\Models\Appointment;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Spatie\CalendarLinks\Link;

class BookingConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Appointment $appointment
    ) { }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $from = DateTime::createFromFormat('Y-m-d H:i:s',$this->appointment->app_start_time);
        $to = DateTime::createFromFormat('Y-m-d H:i:s',$this->appointment->app_end_time);
        $title = 'Appointment at PERNECZKY BarberShop';
        $description = nl2br("Service: " . $this->appointment->service->name . "\nBarber: " . $this->appointment->barber->getName());

        $link = Link::create($title, $from, $to)
            ->description($description)
            ->address('1082 Budapest, Corvin sétány 5.');
        $icsContent = $link->ics([], ['format' => 'file']);
        
        return (new MailMessage)
            ->subject('Appointment Booked Succesfully')
            ->view('emails.booking_stored',[
                'appointment' => $this->appointment,
                'notifiable' => $notifiable
            ])->attachData($icsContent, 'appointment.ics', [
                'mime' => 'text/calendar'
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'appointment_user_first_name' => $this->appointment->user->first_name,
            'appointment_time' => $this->appointment->app_start_time,
            'appointment_barber' => $this->appointment->barber,
            'appointment_duration' => $this->appointment->service->duration
        ];
    }
}
