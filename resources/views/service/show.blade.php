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
                <x-button role="ctaMain" :disabled="isset($service->deleted_at)">Save changes</x-button>
                </form>

                @if ($service->deleted_at)
                    <form action="{{ route('services.restore',$service) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <x-button role="restore">Restore service</x-button>
                    </form>
                @else
                    <form action="{{ route('services.destroy',$service) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <x-button role="destroy">Delete service</x-button>
                    </form>
                @endif
            </div>
        
    </x-card>

    <h2 class="font-bold text-2xl mb-4">Details about this service</h2>

    <x-card>
        <div class="mb-4">
            <h3 class="text-xl font-bold mb-4">General details</h3>
            <ul class="*:mb-2">
                <li>Created at: {{ $service->created_at }}</li>
                <li>Updated at: {{ $service->updated_at }}</li>
            </ul>
        </div>

        <hr class="mb-4">
        
        <div class="flex justify-between gap-4">
            <div class="flex-1">
                <h3 class="text-xl font-bold mb-4">Statistics from the past</h3>
                <ul class="*:mb-2">
                    <li>Booked: {{ number_format($previousStats['numBookings'],thousands_separator:' ') }} times - <a href="{{ route('bookings.index',['service' => $service->id, 'time_window' => 'previous']) }}" class="text-blue-700 hover:underline font-bold">Show bookings</a></li>
                    <li>Average price: {{ number_format($previousStats['avgPrice'],$previousStats['avgPrice'] != floor($previousStats['avgPrice']) ? 2 : 0, thousands_separator:' ') }} HUF</li>
                    <li>Revenue generated from this service: {{ number_format($previousStats['sumPrice'], $previousStats['sumPrice'] != floor($previousStats['sumPrice']) ? 2 : 0,thousands_separator:' ') }} HUF</li>
                </ul>
            </div>
            <div class="border-l"></div>
            <div class="flex-1">
                <h3 class="text-xl font-bold mb-4">Statistics from the future</h3>
                <ul class="*:mb-2">
                    <li>Booked: {{ number_format($upcomingStats['numBookings'],thousands_separator:' ') }} times - <a href="{{ route('bookings.index',['service' => $service->id, 'time_window' => 'upcoming']) }}" class="text-blue-700 hover:underline font-bold">Show bookings</a></li>
                    <li>Average price: {{ number_format($upcomingStats['avgPrice'],$upcomingStats['avgPrice'] != floor($upcomingStats['avgPrice']) ? 2 : 0, thousands_separator:' ') }} HUF</li>
                    <li>Revenue estimated from this service: {{ number_format($upcomingStats['sumPrice'],  $upcomingStats['sumPrice'] != floor($upcomingStats['sumPrice']) ? 2 : 0,thousands_separator:' ') }} HUF</li>
                </ul>
            </div>
        </div>
    </x-card>
</x-user-layout>