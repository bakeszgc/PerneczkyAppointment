<?php

namespace App\Notifications;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Barber;
use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookingUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public array $oldAppointment,
        public Appointment $newAppointment,
        public Barber|string $updatedBy
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
        
        return (new MailMessage)
            ->subject('Your Appointment Has Been Modified')
            ->view('emails.booking_updated',[
                'oldAppointment' => $this->oldAppointment,
                'newAppointment' => $this->newAppointment,
                'notifiable' => $notifiable,
                'updatedBy' => $this->updatedBy
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
