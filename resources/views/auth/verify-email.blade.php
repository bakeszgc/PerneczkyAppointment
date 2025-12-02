<x-user-layout title="{{ __('auth.verify') }}">
    <x-breadcrumbs :links="[
        __('auth.verify') => ''
    ]"/>

    <x-headline class="mb-4">
        {{ __('auth.verify_your_email_address') }}
    </x-headline>

    <x-card class="mb-4 text-center py-8 max-md:py-6">
        <h2 class="font-bold text-2xl max-md:text-xl mb-4">
            {{ __('auth.verify_p1') }}
        </h2>
        <p class="mb-2">
            {{ __('auth.verify_p2') }}
        </p>
        <p class="mb-4">
            {{ __('auth.verify_p3') }}
        </p>
        <div class="flex justify-center">
            <form action="{{ route('verification.send') }}" method="POST">
                @csrf
                <x-button role="ctaMain">
                    {{ __('auth.resend_verif') }}
                </x-button>
            </form>
        </div>
    </x-card>
</x-user-layout>