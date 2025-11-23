<x-user-layout currentView="admin" title="{{ __('admin.new_service') }}">
    <x-breadcrumbs :links="[
        __('home.admin_dashboard') => route('admin'),
        __('admin.services') => route('services.index'),
        __('admin.new_service') => ''
    ]"/>

    <x-headline class="mb-4">
        {{ __('admin.create_service_title') }}
    </x-headline>

    <x-card class="mb-6">
        <form action="{{ route('services.store') }}" method="POST">
            @csrf
            <div class="flex flex-col mb-4">
                <x-label for="name_hu">
                    {{ __('admin.name_hu') }}*
                </x-label>
                <x-input-field id="name_hu" name="name_hu" :value="old('name_hu') ?? ''" class="createInput reqInput"></x-input-field>
                @error('name_hu')
                    <p class=" text-red-500">{{$message}}</p>
                @enderror
            </div>

            <div class="flex flex-col mb-4">
                <x-label for="name_en">
                    {{ __('admin.name_en') }}*
                </x-label>
                <x-input-field id="name_en" name="name_en" :value="old('name_en') ?? ''" class="createInput reqInput"></x-input-field>
                @error('name_en')
                    <p class=" text-red-500">{{$message}}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 max-md:grid-cols-1 gap-4 mb-4">
                <div class="flex flex-col">
                    <x-label for="price">
                        {{ __('barber.price_in_huf') }}*
                    </x-label>
                    <x-input-field type="number" id="price" name="price" :value="old('price') ?? ''" class="createInput reqInput"></x-input-field>
                    @error('price')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>

                <div class="flex flex-col">
                    <x-label for="duration">
                        {{ __('admin.duration_in_minutes') }}*
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
                        {{ __('admin.visible_for_everyone') }}
                    </label>
                    @error('is_visible')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>

                <div>
                    <p>* {{ __('auth.required_fields') }}</p>
                </div>
            </div>

            <div class="flex gap-2">
                <x-button role="createMain" id="createButton" :disabled="true">
                    {{ __('barber.create') }}
                </x-button>
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