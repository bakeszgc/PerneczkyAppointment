<x-user-layout currentView="user" title="{{ __('appointments.success') }}">
    <x-breadcrumbs :links="[
        __('appointment.success') => ''
    ]" />
    <x-headline class="mb-4">
        {{ __('appointments.appointment_booked_successfully') }}
    </x-headline>
    <x-card class="mb-6">
        <h3 class="text-lg max-md:text-base mb-4 font-bold">
            ✅ {{__('appointments.everything_is_done') . ', ' . $user->first_name }}!
        </h3>
        <p class="mb-4">
            {{ __('appointments.success_p1') }}
        </p>
        <p>
            {{ __('appointments.success_p2') }}
        </p>
    </x-card>

    <h2 class="text-xl max-md:text-lg font-bold mb-4">
        {{ __('appointments.why_should_i_sign_up') }}
    </h2>

    <div class="grid grid-cols-3 max-md:grid-cols-1 gap-4 mb-6">
        <x-card>
            <h3 class="font-bold text-lg max-md:text-base mb-4">
                {{ __('appointments.sign_up_t1') }}
            </h3>
            <p class="mb-2">
                {{ __('appointments.sign_up_p1a') }}
            </p>
            <p>
                {{ __('appointments.sign_up_p1b') }}
            </p>
        </x-card>

        <x-card>
            <h3 class="font-bold text-lg max-md:text-base mb-4">
                {{ __('appointments.sign_up_t2') }}
            </h3>
            <p class="mb-2">
                {{ __('appointments.sign_up_p2a') }}
            </p>
            <p>
                {{ __('appointments.sign_up_p2b') }}
            </p>
        </x-card>

        <x-card>
            <h3 class="font-bold text-lg max-md:text-base mb-4">
                {{ __('appointments.sign_up_t3') }}
            </h3>
            <p>
                {{ __('appointments.sign_up_p3') }}
            </p>
        </x-card>
    </div>

    <div class="mb-2">
        <x-link-button :full="true" role="ctaMain" :link="route('register',['first_name' => $user->first_name, 'email' => $user->email ])">
            {{ __('appointments.sign_up_now') }}
        </x-link-button>
    </div>
    <div class="mb-4 text-center">
        <a href="{{ route('home') }}" class="text-blue-700 hover:underline max-md:text-sm">
            {{ __('appointments.go_home') }}
        </a>
    </div>
</x-user-layout>