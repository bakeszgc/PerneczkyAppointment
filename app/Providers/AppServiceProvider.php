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
            return (new MailMessage)
                ->subject('Verify Your Email Address')
                ->greeting('Hi ' . $notifiable->first_name . ',')
                ->line("Thank you for signing up. We are excited to help you look your best!")
                ->line('Please confirm your email address by clicking the button below:')
                ->action('Verify My Email', $url)
                ->line('If you didn’t create an account with us, no further action is required. Feel free to message us to email@address.com if you need assistance.');
        });
    }
}
