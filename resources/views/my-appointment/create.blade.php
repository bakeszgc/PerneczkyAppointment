<x-user-layout title="Book an Appointment - Perneczky BarberShop">
    <x-breadcrumbs :links="[
        'Book an Appointment' => ''
    ]"/>
    <x-headline class="mb-4">Book an Appointment</x-headline>
    <x-card class="text-center">
        <div class="text-center mb-4">
            <h2 class="font-bold text-xl mb-4">Are you a returning customer?</h2>
            <div class="flex items-center gap-2 justify-center">
                <x-link-button role="ctaMain" link="{{ route('login') }}">Log In</x-link-button>
                <p>or</p>
                <x-link-button role="active" link="{{ route('register') }}">Create an Account</x-link-button>
            </div>
            
        </div>
        <div class="mb-4">
            <hr class="mx-auto w-32">
        </div>
        <a href="{{route('my-appointments.create.barber')}}" class="text-blue-700 hover:underline">
            Continue without an account
        </a>
    </x-card>
</x-user-layout>