<x-email-layout>
    <h1 class="mb-4">
        {{ __('mail.hi') . ' ' . $notifiable->first_name }},
    </h1>

    <p class="mb-8">
        {{ __('mail.welcome') }}
    </p>

    <div class="text-center mb-8">
        <a href="{{ $url }}" id="ctaButton" target="_blank">
            {{ __('mail.verify_email_address') }}
        </a>
    </div>

    <p class="mb-8">
        {{ __('mail.account_not_created_1') . ' ' . __('mail.booking_stored_p_3a') }}
        <a href="mailto:{{ env('COMPANY_MAIL') }}" class="link">{{ env('COMPANY_MAIL') }}</a>
        {{ __('mail.booking_stored_p_3b') }}
        <a href="tel:{{ str_replace(' ','',env('COMPANY_PHONE')) }}" class="link">
            {{ env('COMPANY_PHONE') }}
        </a>{{ __('mail.booking_stored_p_3c') }}
    </p>

    <hr class="mb-8" />

    <p id="linkTrouble">
        {{ __('mail.url_error_p_1') . __('mail.verify_email_address') . __('mail.url_error_p_2') }}
        <a href="{{ $url }}" class="link word-break">{{ $url }}</a>
    </p>
</x-email-layout>