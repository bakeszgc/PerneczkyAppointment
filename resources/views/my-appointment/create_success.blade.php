<x-user-layout currentView="user" title="Success">
    <x-breadcrumbs :links="[
        'Success' => ''
    ]" />
    <x-headline class="mb-4">Appointment booked successfully</x-headline>
    <x-card class="mb-6">
        <h3 class="text-lg max-md:text-base mb-4 font-bold">✅ Everything is done, {{ $user->first_name }}!</h3>
        <p class="mb-4">Thanks for choosing us for your next appointment! We promise we will take a good care of your hair. See you soon!</p>
        <p>We have sent you an email including the most important details of your booking. If you want to have access to multiple awesome features that makes your life a lot easier, then consider creating an account!</p>
    </x-card>

    <h2 class="text-xl max-md:text-lg font-bold mb-4">Why should I sign up?</h2>

    <div class="grid grid-cols-3 max-md:grid-cols-1 gap-4 mb-6">
        <x-card>
            <h3 class="font-bold text-lg max-md:text-base mb-4">View and manage your appointments</h3>
            <p>With an account you can log in on our page and check your upcoming and previous appointments at one place. These can even be modified and cancelled there by you.</p>
        </x-card>

        <x-card>
            <h3 class="font-bold text-lg max-md:text-base mb-4">View and manage your appointments</h3>
            <p>With an account you can log in on our page and check your upcoming and previous appointments at one place. These can even be modified and cancelled there by you.</p>
        </x-card>

        <x-card>
            <h3 class="font-bold text-lg max-md:text-base mb-4">Get a free cut after 10 appointments</h3>
            <p>Yes, you heard it right! We want to make to feel our regulars special, so after every 10th appointments the next one is on the house! This is an upcoming feature though.</p>
        </x-card>
    </div>

    <div class="mb-2">
        <x-link-button :full="true" role="ctaMain" link="{{ route('register') }}?first_name={{ $user->first_name }}&email={{ $user->email }}">Create an account</x-link-button>
    </div>
    <div class="mb-4 text-center">
        <a href="{{ route('home') }}" class="text-blue-700 hover:underline max-md:text-sm">Go back to home</a>
    </div>
</x-user-layout>