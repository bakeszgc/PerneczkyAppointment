<x-user-layout currentView="admin" :title="$service->name . ' - '">
    <x-breadcrumbs :links="[
        'Admin Dashboard' => route('admin'),
        'Manage Services' => route('services.index'),
        $service->name => ''
    ]"/>

    <x-headline class="mb-4">{{ $service->name }}</x-headline>

    <x-card>
        <form action="{{ route('services.update',$service) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="flex flex-col mb-4">
                <x-label for="name">
                    Name
                </x-label>
                <x-input-field id="name" name="name" :value="old('name') ?? $service->name"></x-input-field>
                @error('name')
                    <p class=" text-red-500">{{$message}}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="flex flex-col">
                    <x-label for="price">
                        Price (in HUF)
                    </x-label>
                    <x-input-field type="number" id="price" name="price" :value="old('price') ?? $service->price"></x-input-field>
                    @error('price')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>

                <div class="flex flex-col">
                    <x-label for="duration">
                        Duration (in minutes)
                    </x-label>
                    <x-input-field type="number" id="duration" name="duration" :value="old('duration') ?? $service->duration"></x-input-field>
                    @error('duration')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4 flex gap-2">
                <x-input-field type="checkbox" name="is_visible" id="is_visible" :checked="$service->is_visible" value="is_visible"></x-input-field>
                <label for="is_visible">
                    Visible for everyone
                </label>
                @error('is_visible')
                    <p class=" text-red-500">{{$message}}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <x-button role="ctaMain">Save Changes</x-button>
                <x-button role="destroy">Delete Service</x-button>
            </div>
        </form>
    </x-card>
    <x-card>
        <h2>Statistics about this service</h2>
        <ul>
            <li>Service booked: {{ $number }} times</li>
            <li>Revenue generated from this service: {{ number_format($revenue,thousands_separator:' ') }} Ft</li>
        </ul>
    </x-card>
</x-user-layout>