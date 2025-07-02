<x-user-layout title="Sign In - ">
    <x-breadcrumbs :links="[
        'Sign In' => ''
    ]" />

    <x-headline class="mb-4">Sign in to Your Account</x-headline>

    <x-card class="mb-8">
        <div class="m-4">
            <form action="{{route('auth.store')}}" method="POST">
                @csrf
                <div class="mb-4">
                    <x-label for="email">Email</x-label>
                    <x-input-field type="email" name="email" id="email" value="{{old('email')}}" class="w-full"/>
                    @error('email')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <x-label for="password">Password</x-label>
                    <x-input-field type="password" name="password" id="password" class="w-full"/>
                    @error('password')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>

                <div class="mb-4 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <x-input-field id="remember" name="remember" type="checkbox" />
                        <x-label for="remember">Remember me</x-label>
                    </div>
                    <div>
                        <a href="{{ route('password.request') }}" class="text-[#0018d5] hover:underline">Forgot your password?</a>
                    </div>
                </div>

                <x-button role="ctaMain" :full="true">Sign in</x-button>
            </form>

            <p class="text-center font-semibold mt-2">
                Don't have an account yet?
                <a href="{{ route('register') }}" class=" text-blue-700 hover:underline">
                    Create one here!
                </a>
            </p>
        </div>
        
    </x-card>
</x-user-layout>