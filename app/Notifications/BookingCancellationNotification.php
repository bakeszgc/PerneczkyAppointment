<?php

namespace App\Notifications;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Barber;
use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\App;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookingCancellationNotification extends Notification // implements ShouldQueue
{
    // use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Appointment $appointment,
        public User|Barber|string $cancelledBy,
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
        if ($notifiable->lang_pref) {
            App::setLocale($notifiable->lang_pref);
        } else {
            App::setLocale('en');
        }

        switch (is_string($this->cancelledBy) ? 'Admin' : get_class($this->cancelledBy)) {
            case 'App\Models\User':
                $subject = __('mail.your_booking_cancelled');
            break;

            case 'App\Models\Barber':
            case 'Admin':
                if ($notifiable->id == $this->appointment->barber->user_id) {
                    $subject = __('mail.your_booking_cancelled');
                } else {
                    $subject = __('mail.your_appointment_cancelled');
                }
            break;
        }

        return (new MailMessage)
            ->subject($subject)
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
