<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\Models\Barber;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCancellationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Appointment $appointment,
        public User|Barber|string $cancelledBy
    )
    {
        //
    }

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
        $date = Carbon::parse($this->appointment->app_start_time)->format('G:i') . ' on ' . Carbon::parse($this->appointment->app_start_time)->format('Y-m-d');

        switch (is_string($this->cancelledBy) ? 'Admin' : get_class($this->cancelledBy)) {
            case 'App\Models\User':
                $appointmentType = 'booking';
            break;

            case 'App\Models\Barber':
            case 'Admin':
                $appointmentType = 'appointment';
            break;
        }

        return (new MailMessage)
            ->subject('Your ' . strtolower($appointmentType) . ' has been cancelled')
            ->view('emails.booking_cancelled',[
                'appointment' => $this->appointment,
                'cancelledBy' => $this->cancelledBy,
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
            //
        ];
    }
}
