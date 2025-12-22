<?php

namespace App\Notifications;

use DateTime;
use Carbon\Carbon;
use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Spatie\CalendarLinks\Link;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentReminderNotification extends Notification // implements ShouldQueue
{
    // use Queueable;

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
        $title = 'Appointment at ' . env('APP_NAME');
        $description = nl2br("Service: " . $this->appointment->service->getName() . "\nBarber: " . $this->appointment->barber->getName());

        $link = Link::create($title, $from, $to)
            ->description($description)
            ->address(env('STORE_ADDRESS'));
        $icsContent = $link->ics([], ['format' => 'file']);
        
        return (new MailMessage)
            ->subject('Ready for a fresh cut?')
            ->view('emails.booking_reminder',[
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
            'appointment_time' => $this->appointment->app_start_time
        ];
    }
}
