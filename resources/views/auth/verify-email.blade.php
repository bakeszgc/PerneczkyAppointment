<x-user-layout title="Verify Your Email Address - ">
    <x-breadcrumbs :links="[
        'Verify Your Email Address' => ''
    ]"/>

    <h1 class="font-extrabold text-4xl mb-4">Verify Your Email Address</h1>
    <x-card class="mb-4">
        <div class=" text-center my-4">
            <h2 class="font-bold text-2xl mb-4">Looks like you have not verified your email address</h2>
            <p>The page you were trying to access requires your email to be verified.</p>
            <p class="mb-4">Please check your inbox for the verification email or request a new one below!</p>
            <div class="flex justify-center">
                <form action="{{ route('verification.send') }}" method="POST">
                    @csrf
                    <x-button role="ctaMain">
                        Resend the verification email
                    </x-button>
                </form>
            </div>
        </div>
    </x-card>
</x-user-layout>