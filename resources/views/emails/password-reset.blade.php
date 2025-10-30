<x-email-layout>

    <h1 class="mb-4">Hi {{ $notifiable->first_name }},</h1>

    <p class="mb-8">You're receiving this email because we got a password reset request for your account. Click on the button below to change your password! This link will expire in 60 minutes.</p>

    <div class="text-center mb-8">
        <a href="{{ route('password.reset',['token' => $token, 'email' => $notifiable->email]) }}" id="ctaButton" target="_blank">Reset password</a>
    </div>

    <p class="mb-4">If you didn't request a password reset, no further action is required.</p>

    <p class="mb-8">If you have any questions, need to reschedule, or require assistance, feel free to contact us at <a href="mailto:info@perneczkybarbershop.hu" class="link">info@perneczkybarbershop.hu</a> or call us at <a href="tel:+36704056079" class="link">+36 70 405 6079</a>.</p>

    <hr class="mb-8" />

    <p id="linkTrouble">
        If you're having trouble clicking the "Reset password" button, copy and paste the URL below into your web browser: <a href="{{ route('password.reset',['token' => $token, 'email' => $notifiable->email]) }}" class="link word-break">{{ route('password.reset',['token' => $token, 'email' => $notifiable->email]) }}</a>
    </p>
</x-email-layout>