<x-email-layout>
    <h1 class="mb-4">Hi {{ $notifiable->first_name }},</h1>

    <p class="mb-8">Welcome to PERNECZKY BarberShop, thank you for creating an account! Please click on the button below to verify your email address so we will know it's actually you.</p>

    <div class="text-center mb-8">
        <a href="{{ $url }}" id="ctaButton" target="_blank">Verify email address</a>
    </div>

    <p class="mb-8">If you did not create an account, no further action is required from you. If you have any questions or require assistance, feel free to contact us at <a href="mailto:info@perneczkybarbershop.hu" class="link">info@perneczkybarbershop.hu</a> or call us at <a href="tel:+36704056079" class="link">+36 70 405 6079</a>.</p>

    <hr class="mb-8" />

    <p id="linkTrouble">
        If you're having trouble clicking the "Verify email address" button, copy and paste the URL below into your web browser: <a href="{{ $url }}" class="link word-break">{{ $url }}</a>
    </p>
</x-email-layout>