<?php

namespace App\Notifications;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCancellationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Appointment $appointment,
        public string $cancelledBy
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
        $date = Carbon::parse($this->appointment->app_start_time)->format('Y. m. d.') . ' at ' . Carbon::parse($this->appointment->app_start_time)->format('G:i');

        $notName = $notifiable->barber->display_name ?? $notifiable->first_name;

        if ($this->cancelledBy === 'user') {
            $name = $this->appointment->user->first_name;
            $url = route('appointments.cancelled');
            $ctaText = 'Cancelled Bookings';
        } else {
            $name = $this->appointment->barber->display_name ?? $this->appointment->user->first_name;
            $url = route('my-appointments.create');
            $ctaText = 'Book a New Appointment';
        }

        return (new MailMessage)
                    ->subject('Your Appointment Has Been Cancelled')
                    ->greeting('Hi '. $notName . ',')
                    ->line("Unfortunately, {$name} has cancelled your appointment for {$date}.")
                    ->action($ctaText, $url)
                    ->line('If you have any questions, need to reschedule, or require assistance, feel free to contact us at email or call us at phonenum.');
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
