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
                $notifiableName = $notifiable->barber->getName();
                $cancelledByName = $this->cancelledBy->first_name;

                $ctaText = "View {$cancelledByName}'s Booking";
                $url = route('appointments.show',$this->appointment);

                $text1 = "You can view {$cancelledByName}'s booking by clicking on the button below:";
                $text2 = 'Try contacting your client about rescheduling their appointment.';
            break;

            case 'App\Models\Barber':
            case 'Admin':
                $appointmentType = 'appointment';
                $notifiableName = $notifiable->first_name;
                $cancelledByName = is_string($this->cancelledBy) ? 'an admin' : $this->cancelledBy->getName();

                $ctaText = 'Book a New Appointment';
                $url = route('my-appointments.create.barber.service',[
                    'barber_id' => $this->appointment->barber_id,
                    'service_id' => $this->appointment->service_id
                ]);

                $text1 = "Don't forget to book another one by clicking on the button below:";
                $text2 = "If you have any questions, need to reschedule, or require assistance, feel free to contact us at info@perneczkybarbershop.hu or call us at +36 70 405 6079.";
            break;
        }

        return (new MailMessage)
            ->subject('Your ' . ucfirst($appointmentType) . ' Has Been Cancelled')
            ->greeting('Hi '. $notifiableName . ',')
            ->line("Unfortunately, {$cancelledByName} has cancelled " . (!is_string($this->cancelledBy) && get_class($this->cancelledBy) == 'App\Models\User' ? 'their' : 'your') . " appointment for {$date}. {$text1}")
            ->action($ctaText, $url)
            ->line($text2);
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
