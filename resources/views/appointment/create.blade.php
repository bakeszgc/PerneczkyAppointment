<x-layout title="Create New Appointment - Perneczky BarberShop">
    <x-breadcrumbs :links="[
        'Appointments' => route('appointments.index'),
        'New Appointment' => ''
    ]"/>
    <h1 class="font-bold text-4xl mb-4">Create New Appointment - Find the user</h1>
    
    <x-card>
        <form action="" method="POST">
            <div>
                <x-label for="name">Name</x-label>
                <x-input-field name="name"></x-input-field>

            </div>
        
        <x-label for="customer">Choose a Customer</x-label>
        <select name="customer" id="customer">
            @foreach ($users as $user)
                <option value="{{$user->id}}">{{$user->first_name . " " . $user->last_name}}</option>
            @endforeach
            
        </select>
        </form>
        
    </x-card>
</x-layout>