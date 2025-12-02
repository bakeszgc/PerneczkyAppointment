<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            if (auth()->user() == $notifiable) {
                $notifiable->updateLangPref();
            }
            return (new MailMessage)
                ->subject(__('mail.verify_your_email_address'))
                ->view('emails.email_verification', [
                    'notifiable' => $notifiable,
                    'url' => $url
                ]);
        });
    }
}
