<x-user-layout title="Verify your email address">
    <x-breadcrumbs :links="[
        'Verify your email address' => ''
    ]"/>

    <x-headline class="mb-4">Verify Your Email Address</x-headline>

    <x-card class="mb-4 text-center py-8 max-md:py-6">
        <h2 class="font-bold text-2xl max-md:text-xl mb-4">Looks like you have not verified your email address</h2>
        <p class="mb-2">The page you were trying to access requires your email to be verified.</p>
        <p class="mb-4">Please check your inbox for the verification email or request a new one below!</p>
        <div class="flex justify-center">
            <form action="{{ route('verification.send') }}" method="POST">
                @csrf
                <x-button role="ctaMain">
                    Resend the verification email
                </x-button>
            </form>
        </div>
    </x-card>
</x-user-layout>