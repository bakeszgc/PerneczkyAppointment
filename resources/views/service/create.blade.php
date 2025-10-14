<x-user-layout currentView="admin" title="New service">
    <x-breadcrumbs :links="[
        'Admin dashboard' => route('admin'),
        'Services' => route('services.index'),
        'New service' => ''
    ]"/>

    <x-headline class="mb-4">Create a new service</x-headline>

    <x-card class="mb-6">
        <form action="{{ route('services.store') }}" method="POST">
            @csrf
            <div class="flex flex-col mb-4">
                <x-label for="name">
                    Name*
                </x-label>
                <x-input-field id="name" name="name" :value="old('name') ?? ''" class="createInput reqInput"></x-input-field>
                @error('name')
                    <p class=" text-red-500">{{$message}}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 max-md:grid-cols-1 gap-4 mb-4">
                <div class="flex flex-col">
                    <x-label for="price">
                        Price (in HUF)*
                    </x-label>
                    <x-input-field type="number" id="price" name="price" :value="old('price') ?? ''" class="createInput reqInput"></x-input-field>
                    @error('price')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>

                <div class="flex flex-col">
                    <x-label for="duration">
                        Duration (in minutes)*
                    </x-label>
                    <x-input-field type="number" id="duration" name="duration" :value="old('duration') ?? ''" class="createInput reqInput"></x-input-field>
                    @error('duration')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4 flex justify-between items-center">
                <div class="flex gap-2 items-center">
                    <x-input-field type="checkbox" name="is_visible" id="is_visible" value="is_visible" class="createInput"></x-input-field>
                    <label for="is_visible">
                        Visible for everyone
                    </label>
                    @error('is_visible')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>

                <div>
                    <p>* Required fields</p>
                </div>
            </div>

            <div class="flex gap-2">
                <x-button role="createMain" id="createButton">Create</x-button>
            </div>
        </form>
    </x-card>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const createButton = document.getElementById('createButton');
            const inputs = document.querySelectorAll('.createInput');
            const reqInputs = document.querySelectorAll('.reqInput');
            
            enableButtonIfInputsFilled(createButton,inputs,reqInputs);
        });
    </script>
</x-user-layout>