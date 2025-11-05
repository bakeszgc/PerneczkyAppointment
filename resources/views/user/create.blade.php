<x-user-layout title="Register">

    <x-breadcrumbs :links="[
        'Register' => ''
    ]" />

    <x-headline class="mb-4">Create a new account</x-headline>

    <x-card class="mb-8 p-8 max-md:p-4">
        <form action="{{route('user.store')}}" method="POST">
            @csrf

            <div class=" mb-4 grid grid-cols-2 max-md:grid-cols-1 gap-4">
                <div>
                    <x-label for="first_name">First name*</x-label>
                    <x-input-field name="first_name" id="first_name" value="{{old('first_name') ?? request('first_name')}}" placeholder="John" autoComplete="on" class="w-full regInput"/>
                    @error('first_name')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>
                <div>
                    <x-label for="last_name">Last name*</x-label>
                    <x-input-field name="last_name" id="last_name" autoComplete="on" value="{{old('last_name')}}" placeholder="Example" class="w-full regInput"/>
                    @error('last_name')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>
                
            </div>

            <div class="mb-4 grid grid-cols-2  max-md:grid-cols-1 gap-4">
                <div>
                    <x-label for="date_of_birth">Date of birth*</x-label>
                    <x-input-field type="date" name="date_of_birth" id="date_of_birth" value="{{old('date_of_birth')}}" autoComplete="bday" class="w-full regInput"/>
                    @error('date_of_birth')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>
                <div>
                    <x-label for="telephone_number">Telephone number*</x-label>
                    <x-input-field type="tel" name="telephone_number" id="telephone_number" value="{{old('telephone_number')}}" placeholder="+36123456789" autoComplete="on" class="w-full regInput"/>
                    @error('telephone_number')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <x-label for="email">Email*</x-label>
                <x-input-field type="email" name="email" id="email" autoComplete="on" value="{{old('email') ?? request('email')}}" placeholder="john@example.com" class="w-full regInput"/>
                @error('email')
                    <p class=" text-red-500">{{$message}}</p>
                @enderror
            </div>
            
            <div class="flex gap-4 mb-4 max-md:mb-4 max-md:flex-col">
                <div class="flex-grow">
                    <div class="mb-4">
                        <x-label for="password">Password*</x-label>
                        <x-input-field type="password" name="password" id="password" class="w-full regInput"/>
                        @error('password')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    <div>
                        <x-label for="password_confirmation">Confirm password*</x-label>
                        <x-input-field type="password" name="password_confirmation" id="password_confirmation" class="w-full regInput"/>
                        @error('password_confirmation')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                </div>

                <x-password-checklist class="flex-grow-0" />
            </div>

            @if (request('from'))
                @foreach ($prevAttributes as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <input type="hidden" name="from" value="{{ request('from') }}">
            @endif

            <div class="flex gap-2 items-center mb-4">
                <x-input-field type="checkbox" name="policy_checkbox" id="policy_checkbox" value="1" class="regInput reqInput"/>
                <label for="policy_checkbox" class="flex-1">I have read and I accept the terms & conditions and the <a href="{{ route('privacy') }}" target="_blank"class="text-blue-700 hover:underline">privacy policy</a>.*</label>

                @error('policy_checkbox')
                    <p class=" text-red-500 text-right">{{$message}}</p>
                @enderror
            </div>

            <x-button role="ctaMain" :full="true" :disabled="true" id="regButton">Register</x-button>
        </form>

        <p class="text-center mt-2 max-sm:text-xs">
            Already have an account?
            <a href="{{ route('login') }}" class=" text-blue-700 hover:underline">
                Sign in here!
            </a>
        </p>        
    </x-card>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // SUBMIT BUTTON ENABLER
            const regButton = document.getElementById('regButton');
            const regInputs = document.querySelectorAll('.regInput');
            const passInput = document.getElementById('password');
            const passConfInput = document.getElementById('password_confirmation');
            
            regButton.disabled = true;

            regInputs.forEach(input => {
                input.addEventListener('input', function () {
                    const passwordChecked = checkPassword(passInput, passConfInput);
                    const allFilled = allInputsFilled(regInputs);

                    if (allFilled && passwordChecked) {
                        regButton.disabled = false;
                    } else {
                        regButton.disabled = true;
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