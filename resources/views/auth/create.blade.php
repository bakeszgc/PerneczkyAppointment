<x-user-layout title="Sign in" currentView="user">
    <x-breadcrumbs :links="[
        'Sign in' => ''
    ]" />

    <x-headline class="mb-4">Sign in to your account</x-headline>

    <x-card class="mb-8 p-8 max-md:p-4">
            <form action="{{route('auth.store')}}" method="POST">
                @csrf
                <div class="mb-4">
                    <x-label for="email">Email</x-label>
                    <x-input-field type="email" name="email" id="email" value="{{old('email')}}" autoComplete="on" class="w-full loginInput reqInput"/>
                    @error('email')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <x-label for="password">Password</x-label>
                    <x-input-field type="password" name="password" id="password" autoComplete="on" class="w-full loginInput reqInput"/>
                    @error('password')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>

                <div class="mb-4 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <x-input-field id="remember" name="remember" type="checkbox" value="remember" class="loginInput" />
                        <x-label for="remember">Remember me</x-label>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('password.request') }}" class="text-[#0018d5] hover:underline max-md:text-sm">Forgot your password?</a>
                    </div>
                </div>

                @if (request('from'))
                    @foreach ($prevAttributes as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <input type="hidden" name="from" value="{{ request('from') }}">
                @endif

                <x-button role="ctaMain" :full="true" id="loginButton" :disabled="true">Sign in</x-button>
            </form>

            <p class="text-center mt-2 max-sm:text-xs">
                Don't have an account yet?
                <a href="{{ route('register') }}" class=" text-blue-700 hover:underline">
                    Create one here!
                </a>
            </p>

            <a href="{{ route('auth.redirect', 'google') }}" class="text-blue-700 hover:underline">Login with Google</a>
            <a href="{{ route('auth.redirect', 'facebook') }}" class="text-blue-700 hover:underline">Login with Facebook</a>
    </x-card>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginInputs = document.querySelectorAll('.loginInput');
            const loginButton = document.getElementById('loginButton');
            const reqInputs = document.querySelectorAll('.reqInput');

            enableButtonIfInputsFilled(loginButton,loginInputs,reqInputs);
        });
    </script>
</x-user-layout>