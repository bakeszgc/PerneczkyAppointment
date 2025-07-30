<x-user-layout title="Forgot Password - ">
    <x-breadcrumbs :links="[
        'Sign In' => route('login'),
        'Forgot Password' => ''
    ]" />

    <x-headline class="mb-4">Forgot Password</x-headline>

    <x-card class="mb-8 p-8">
        <form action="{{ route('password.email') }}" method="POST">
            @csrf

            <div class="flex flex-col mb-2">
                <x-label for="email">Your email address</x-label>
                <x-input-field type="email" name="email" id="email"></x-input-field>
                @error('email')
                    <p class="text-red-500">{{$message}}</p>
                @enderror
            </div>

            <p class="mb-4 text-justify">
                Enter your email address that you have used for your account and we will send you a link to change your password. If you still need help please <a href="{{ route('home') }}#contact" class="text-[#0018d5] hover:underline">contact us</a>.
            </p>

            <x-button :full="true" role="ctaMain" :disabled="true" id="submitButton">Send password reset link</x-button>
        </form>
    </x-card>

</x-user-layout>