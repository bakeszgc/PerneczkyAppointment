<x-user-layout currentView="admin" :title="$service->name">
    <x-breadcrumbs :links="[
        'Admin Dashboard' => route('admin'),
        'Manage Services' => route('services.index'),
        $service->name => ''
    ]"/>

    <x-headline class="mb-4">{{ $service->name }} {{ $service->isDeleted() }}</x-headline>

    <x-card class="mb-6">
        <form action="{{ route('services.update',$service) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="flex flex-col mb-4">
                <x-label for="name">
                    Name
                </x-label>
                <x-input-field id="name" name="name" :value="old('name') ?? $service->name" :disabled="isset($service->deleted_at)"></x-input-field>
                @error('name')
                    <p class=" text-red-500">{{$message}}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="flex flex-col">
                    <x-label for="price">
                        Price (in HUF)
                    </x-label>
                    <x-input-field type="number" id="price" name="price" :value="old('price') ?? $service->price" :disabled="isset($service->deleted_at)"></x-input-field>
                    @error('price')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>

                <div class="flex flex-col">
                    <x-label for="duration">
                        Duration (in minutes)
                    </x-label>
                    <x-input-field type="number" id="duration" name="duration" :value="old('duration') ?? $service->duration" :disabled="isset($service->deleted_at)"></x-input-field>
                    @error('duration')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4 flex gap-2">
                <x-input-field type="checkbox" name="is_visible" id="is_visible" :checked="$service->is_visible" value="is_visible" :disabled="isset($service->deleted_at)"></x-input-field>
                <label for="is_visible">
                    Visible for everyone
                </label>
                @error('is_visible')
                    <p class=" text-red-500">{{$message}}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <x-button role="ctaMain" :disabled="isset($service->deleted_at)">Save Changes</x-button>
                </form>

                @if ($service->deleted_at)
                    <form action="{{ route('services.restore',$service) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <x-button role="restore">Restore Service</x-button>
                    </form>
                @else
                    <form action="{{ route('services.destroy',$service) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <x-button role="destroy">Delete Service</x-button>
                    </form>
                @endif
            </div>
        
    </x-card>

    <x-card>
        <h2 class="text-xl font-bold mb-4">Statistics about this service</h2>
        <ul class="*:mb-2">
            <li>Service booked: {{ $number }} times</li>
            <li>Revenue generated from this service: {{ number_format($revenue,thousands_separator:' ') }} Ft</li>
            <li>jöhet ide még valami info</li>
        </ul>
    </x-card>
</x-user-layout>