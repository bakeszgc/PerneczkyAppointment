<x-layout title="Editing {{$appointment->user->first_name}}'s Appointment - Perneczky Barber Shop">
    <x-breadcrumbs :links="[
        'Appointments' => route('appointments.index'),
        $appointment->user->first_name . '\'s Appointment' => route('appointments.show',$appointment),
        'Editing Appointment' => ''
    ]"/>
    <x-card>
        <h1 class="font-bold text-2xl mb-2">
            {{$appointment->user->first_name . " " . $appointment->user->last_name}} #{{$appointment->id}}
        </h1>

        <form action="{{route('appointments.update',$appointment)}}" method="POST">
            @csrf
            @method('PUT')
            <label for="service" class=" font-medium text-lg">
                Choose a service
            </label>
            <select name="service" id="service" class="border border-slate-300 rounded-md p-2 w-full">
                @foreach ($services as $service)
                    <option value="{{$service->id}}" {{$appointment->service->id === $service->id ? "selected=\"selected\"" : ''}}>
                        {{$service->name}}
                    </option>
                @endforeach
            </select>
            
            <label for="comment" class="font-medium text-lg">Comment</label>
            <textarea name="comment" id="comment" class="border border-slate-300 rounded-md p-2 w-full">
                {{$appointment->comment}}
            </textarea>

            <x-button role="create">
                Update
            </x-button>
        </form>
        
    </x-card>
</x-layout>