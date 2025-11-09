<x-user-layout title="{{ __('auth.register') }}">

    <x-breadcrumbs :links="[
        __('auth.register') => ''
    ]" />

    <x-headline class="mb-4">
        {{ __('auth.register_title') }}
    </x-headline>

    <x-card class="mb-8 p-8 max-md:p-4">
        <form action="{{route('user.store')}}" method="POST">
            @csrf

            <div class=" mb-4 grid grid-cols-2 max-md:grid-cols-1 gap-4">
                <div>
                    <x-label for="first_name">
                        {{ __('auth.first_name') }}*
                    </x-label>
                    <x-input-field name="first_name" id="first_name" value="{{old('first_name') ?? request('first_name')}}" placeholder="{{ __('auth.john') }}" autoComplete="on" class="w-full regInput reqInput"/>
                    @error('first_name')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>
                <div>
                    <x-label for="last_name">
                        {{ __('auth.last_name') }}*
                    </x-label>
                    <x-input-field name="last_name" id="last_name" autoComplete="on" value="{{old('last_name')}}" placeholder="{{ __('auth.example') }}" class="w-full regInput reqInput"/>
                    @error('last_name')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>
                
            </div>

            <div class="mb-4 grid grid-cols-2 max-md:grid-cols-1 gap-4">
                <div>
                    <x-label for="date_of_birth">
                        {{ __('auth.date_of_birth') }}
                    </x-label>
                    <x-input-field type="date" name="date_of_birth" id="date_of_birth" value="{{old('date_of_birth')}}" autoComplete="bday" class="w-full regInput"/>
                    @error('date_of_birth')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>
                <div>
                    <x-label for="telephone_number">
                        {{ __('auth.tel_number') }}
                    </x-label>
                    <x-input-field type="tel" name="telephone_number" id="telephone_number" value="{{old('telephone_number')}}" placeholder="+36123456789" autoComplete="on" class="w-full regInput"/>
                    @error('telephone_number')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <x-label for="email">Email*</x-label>
                <x-input-field type="email" name="email" id="email" autoComplete="on" value="{{old('email') ?? request('email')}}" placeholder="{{ str_replace('á','a',strtolower(__('auth.john'))) . '@' . str_replace('é','e',strtolower(__('auth.example'))) }}.com" class="w-full regInput reqInput"/>
                @error('email')
                    <p class=" text-red-500">{{$message}}</p>
                @enderror
            </div>
            
            <div class="flex gap-4 mb-4 max-md:mb-4 max-md:flex-col">
                <div class="flex-grow">
                    <div class="mb-4">
                        <x-label for="password">{{ __('auth.pw') }}*</x-label>
                        <x-input-field type="password" name="password" id="password" class="w-full regInput reqInput"/>
                        @error('password')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    <div>
                        <x-label for="password_confirmation">
                            {{ __('auth.confirm_pw') }}*
                        </x-label>
                        <x-input-field type="password" name="password_confirmation" id="password_confirmation" class="w-full regInput reqInput"/>
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
                <label for="policy_checkbox" class="flex-1">
                    {{ __('auth.read_and_accept') }}
                    <a href="{{ route('terms') }}" target="_blank" class="text-blue-700 hover:underline">
                        {{ __('auth.terms_and_conditions_acc') }}
                    </a>
                    {{ __('auth.and_the') }}
                    <a href="{{ route('privacy') }}" target="_blank" class="text-blue-700 hover:underline">
                        {{ __('auth.privacy_acc') }}
                    </a>.*
                </label>

                @error('policy_checkbox')
                    <p class=" text-red-500 text-right">{{$message}}</p>
                @enderror
            </div>

            <x-button role="ctaMain" :full="true" :disabled="true" id="regButton">
                {{ __('auth.register') }}
            </x-button>
        </form>

        <p class="text-center mt-2 max-sm:text-xs">
            {{ __('auth.already_account') }}
            <a href="{{ route('login') }}" class=" text-blue-700 hover:underline">
                {{ __('auth.sign_in_here') }}
            </a>
        </p>        
    </x-card>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // SUBMIT BUTTON ENABLER
            const regButton = document.getElementById('regButton');
            const regInputs = document.querySelectorAll('.regInput');
            const reqInputs = document.querySelectorAll('.reqInput');
            const passInput = document.getElementById('password');
            const passConfInput = document.getElementById('password_confirmation');
            
            regButton.disabled = true;

            regInputs.forEach(input => {
                input.addEventListener('input', function () {
                    const passwordChecked = checkPassword(passInput, passConfInput);
                    const allFilled = allInputsFilled(reqInputs);

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