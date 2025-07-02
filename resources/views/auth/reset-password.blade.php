<x-user-layout title="Reset Password - ">
    <x-breadcrumbs :links="[
        'Sign In' => route('login'),
        'Forgot Password' => route('password.request'),
        'Reset Password' => ''
    ]" />

    <x-headline class="mb-4">Reset Password</x-headline>

    <x-card class="mb-8 p-8">
        <form action="{{ route('password.update') }}" method="POST">
            @csrf

            <div class="flex flex-col mb-2">
                <x-label for="email">Your email address *</x-label>
                <x-input-field type="email" name="email" id="email" value="{{ $email }}"></x-input-field>
                @error('email')
                    <p class="text-red-500">{{$message}}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <div class="flex-1">
                    <div class="flex flex-col mb-2">
                        <x-label for="password">Your new password *</x-label>
                        <x-input-field type="password" name="password" id="password"></x-input-field>
                        @error('password')
                            <p class="text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col mb-2">
                        <x-label for="password_confirmation">Confirm your new password *</x-label>
                        <x-input-field type="password" name="password_confirmation" id="password_confirmation"></x-input-field>
                        @error('password_confirmation')
                            <p class="text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex-grow-0">
                    <span class="font-semibold text-base">Your password must contain</span>
                    <ul class="list-disc *:ml-6 mb-4">
                        <li>
                            at least one <span class="font-semibold">undercase letter</span>
                        </li>
                        <li>
                            at least one <span class="font-semibold">uppercase letter</span>
                        </li>
                        <li>
                            at least one <span class="font-semibold">number</span>
                        </li>
                        <li>
                            and be at least <span class="font-semibold">8 characters long</span>
                        </li>
                    </ul>
                    Fields marked with * are <span class="font-semibold">required</span>
                </div>
            </div>

            <input type="hidden" name="token" value="{{ $token }}">

            <p class="mb-4 text-justify">
                Enter your email address that you have used for your account and we will send you a link to change your password. If you still need help please <a href="{{ route('home') }}#contact" class="text-[#0018d5] hover:underline">contact us</a>.
            </p>

            <x-button :full="true" role="ctaMain">Reset your password</x-button>
        </form>
    </x-card>

</x-user-layout>