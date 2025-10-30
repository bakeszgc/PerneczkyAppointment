<x-user-layout title="Reset password" currentView="user">
    <x-breadcrumbs :links="[
        'Sign in' => route('login'),
        'Forgot password' => route('password.request'),
        'Reset password' => ''
    ]" />

    <x-headline class="mb-4">Reset password</x-headline>

    <x-card class="mb-8 p-8 max-md:p-4">
        <form action="{{ route('password.update') }}" method="POST">
            @csrf

            <div class="flex flex-col mb-2">
                <x-label for="email">Email address*</x-label>
                <x-input-field type="email" name="email" id="email" value="{{ $email }}" />
                @error('email')
                    <p class="text-red-500">{{$message}}</p>
                @enderror
            </div>

            <div class="flex max-md:flex-col gap-4 mb-4">
                <div class="flex-grow">
                    <div class="flex flex-col mb-2">
                        <x-label for="password">New password*</x-label>
                        <x-input-field type="password" name="password" id="password" class="w-full resetInput" />
                        @error('password')
                            <p class="text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col mb-2">
                        <x-label for="password_confirmation">Confirm new password*</x-label>
                        <x-input-field type="password" name="password_confirmation" id="password_confirmation" class="w-full resetInput" />
                        @error('password_confirmation')
                            <p class="text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                
                <x-password-checklist class="flex-grow-0" />
            </div>

            <input type="hidden" name="token" value="{{ $token }}">

            <x-button :full="true" role="ctaMain" id="resetButton" :disabled="true">Reset your password</x-button>
        </form>
    </x-card>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // SUBMIT BUTTON ENABLER
            const resetButton = document.getElementById('resetButton');
            const resetInputs = document.querySelectorAll('.resetInput');
            const passInput = document.getElementById('password');
            const passConfInput = document.getElementById('password_confirmation');
            
            resetButton.disabled = true;

            resetInputs.forEach(input => {
                input.addEventListener('input', function () {
                    const passwordChecked = checkPassword(passInput, passConfInput);
                    const allFilled = allInputsFilled(resetInputs);

                    if (allFilled && passwordChecked) {
                        resetButton.disabled = false;
                    }
                });
            });

            
        });

        function checkPassword(passInput, passConfInput) {
            const passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
            return passInput.value != '' &&
                passInput.value == passConfInput.value &&
                passRegex.test(passInput.value);
        }
    </script>
</x-user-layout>