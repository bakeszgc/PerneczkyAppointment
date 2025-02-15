<x-user-layout title="Book an Appointment - Perneczky BarberShop">
    <x-breadcrumbs :links="[
        'Book an Appointment' => ''
    ]"/>
    <h1 class="font-bold text-4xl">Book an Appointment</h1>
    <x-card>
        <div class="text-center mb-4">
            <h2 class="font-bold text-2xl mb-4">Already have an account?</h2>
            <div class="flex items-center gap-2 justify-center">
                <x-link-button role="show" link="">Log In</x-link-button>
                or
                <x-link-button role="show" link="">Sign up</x-link-button>
            </div>
            
        </div>
        <div class="text-center mb-4">
            <hr class=" w-10">
        </div>
        <div class="text-center">
            <h3 class="font-medium text-lg mb-4">Continue without an account</h3>
            <form action="{{route('my-appointments.create.barber')}}" method="GET">
                <label for="email">Add your email address</label>
                <x-input-field type="email" name="email" placeholder="riddim@riddim.com"/>
                <x-button role="createMain">Sumbit</x-button>
            </form>
        </div>
        
    </x-card>
</x-user-layout>