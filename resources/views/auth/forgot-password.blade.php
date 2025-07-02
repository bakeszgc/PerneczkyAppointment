<x-user-layout title="Reset Password - ">
    <x-breadcrumbs :links="[
        'Sign In' => route('login'),
        'Reset Password' => ''
    ]" />

    <x-headline class="mb-4">Reset Your Password</x-headline>

    <x-card class="mb-8 p-8">
        <form action="" method="POST">
            @csrf

            <div class="flex flex-col mb-2">
                <x-label for="email">Your email address</x-label>
                <x-input-field type="email" name="email" id="email"></x-input-field>
                @error('email')
                    <p class="text-red-500">{{$message}}</p>
                @enderror
            </div>

            <p class="mb-4">
                Enter your email address and we'll send you a link to change your password. If you still need help please <a href="{{ route('home') }}#contact">contact us</a>.
            </p>

            <x-button :full="true" role="ctaMain">Reset my password</x-button>
        </form>
    </x-card>

</x-user-layout>