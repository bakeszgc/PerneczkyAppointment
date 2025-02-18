<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Notifications\AppointmentReminderNotification;
use Illuminate\Console\Command;
use Str;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-appointment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends an email reminder to all users who have an appointment for the given day.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $appointments = Appointment::with('user')->whereBetween('app_start_time',[today(),today()->addDay()])->get();
        $appCount = $appointments->count();
        $appLabel = Str::plural('appointment',$appCount);
        $this->info("Found {$appCount} {$appLabel}");

        $appointments->each(
            fn ($appointment) => $appointment->user->notify(
                new AppointmentReminderNotification(
                    $appointment
                )
            )
        );
        $this->info('Reminder notifications sent successfully!');
    }
}
