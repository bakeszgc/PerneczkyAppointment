<x-user-layout title="Forgot password">
    <x-breadcrumbs :links="[
        __('home.sign_in') => route('login'),
        __('auth.forgot_pw_title') => ''
    ]" />

    <x-headline class="mb-4">{{ __('auth.forgot_pw_title') }}</x-headline>

    <x-card class="mb-8 p-8 max-md:p-4">
        <form action="{{ route('password.email') }}" method="POST">
            @csrf

            <div class="flex flex-col mb-2">
                <x-label for="email">{{ __('auth.email') }}</x-label>
                <x-input-field type="email" name="email" id="email" class="w-full"></x-input-field>
                @error('email')
                    <p class="text-red-500">{{$message}}</p>
                @enderror
            </div>

            <p class="mb-4 text-justify text-sm">
                {{ __('auth.forgot_pw_p') }}
                <a href="{{ route('home') }}#contact" class="text-[#0018d5] hover:underline">
                    {{ __('auth.contact_us') }}
                </a>.
            </p>

            <x-button :full="true" role="ctaMain" :disabled="true" id="submitButton">
                {{ __('auth.send_pw_reset_link') }}
            </x-button>
        </form>
    </x-card>

</x-user-layout>