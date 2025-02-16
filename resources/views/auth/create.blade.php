<x-user-layout title="Sign In - ">
    <x-card class="p-8 mb-8">
        <h1 class=" font-bold text-2xl text-center mb-4">Sign in to Your Account</h1>
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
                        <input type="checkbox" name="remember" id="remember">
                        <x-label for="remember">Remember me</x-label>
                    </div>
                    <div>
                        Forgot your password?
                    </div>
                </div>

                <x-button role="loginMain" :full="true">Sign in</x-button>
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