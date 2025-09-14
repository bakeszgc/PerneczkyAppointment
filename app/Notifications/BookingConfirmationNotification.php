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
            ->greeting('Hi '. $this->appointment->user->first_name . ',')
            ->line("Thank you for booking an appointment with us. We are excited to help you look and feel your best! Here are the details of your appointment:")

            ->line('Date & time: ' . Carbon::parse($this->appointment->app_start_time)->format('Y-m-d G:i') . ' (' . $this->appointment->service->duration  . ' minutes)')
            ->line('Service: ' . $this->appointment->service->name . ' (' . number_format($this->appointment->service->price,thousands_separator:' ') . ' HUF)')
            ->line('Your barber: ' . $barberName)

            ->action('View Your Appointment', route('my-appointments.show',$this->appointment->id))

            ->line('Please make sure to arrive at least 5 minutes before your scheduled time to ensure a smooth experience. We accept both credit card and cash at our store. See you soon!')
            ->line('If you have any questions, need to reschedule, or require assistance, feel free to contact us at info@perneczkybarbershop.hu or call us at +36 70 405 6079.');
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
