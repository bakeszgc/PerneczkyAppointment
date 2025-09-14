<?php

namespace App\Notifications;

use Carbon\Carbon;
use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

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
        $barberName = $this->appointment->barber->getName();
        
        return (new MailMessage)
            ->subject('Appointment Booked Succesfully')
            ->view('emails.booking_stored',[
                'appointment' => $this->appointment,
                'notifiable' => $notifiable
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
