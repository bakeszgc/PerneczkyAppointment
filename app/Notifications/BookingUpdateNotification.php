<?php

namespace App\Notifications;

use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Barber;
use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Spatie\CalendarLinks\Link;
use Illuminate\Support\Facades\App;
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
        public Barber|User|string $updatedBy
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
        if ($notifiable->lang_pref) {
            App::setLocale($notifiable->lang_pref);
        } else {
            App::setLocale('en');
        }
        
        $from = DateTime::createFromFormat('Y-m-d H:i:s',$this->newAppointment->app_start_time);
        $to = DateTime::createFromFormat('Y-m-d H:i:s',$this->newAppointment->app_end_time);
        
        $title = __('mail.cal_event_title');
        $description = nl2br(__('mail.service') . ": " . $this->newAppointment->service->getName() . "\n" . __('mail.barber') . ": " . $this->newAppointment->barber->getName());

        $link = Link::create($title, $from, $to)
            ->description($description)
            ->address(env('STORE_ADDRESS'));
        $icsContent = $link->ics([], ['format' => 'file']);
        
        return (new MailMessage)
            ->subject(__('mail.booking_updated_subject'))
            ->view('emails.booking_updated',[
                'oldAppointment' => $this->oldAppointment,
                'newAppointment' => $this->newAppointment,
                'notifiable' => $notifiable,
                'updatedBy' => $this->updatedBy
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
            //
        ];
    }
}
