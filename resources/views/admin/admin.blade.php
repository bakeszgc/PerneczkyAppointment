<x-user-layout currentView="admin" title="Admin Dashboard - ">
    <x-breadcrumbs :links="[
        'Admin Dashboard' => ''
    ]"/>

    <x-headline class="mb-4">Admin Dashboard</x-headline>

    <x-show-card :show="true" type="barbers" class="mb-6">
        <div class="grid grid-cols-3 gap-6 mb-6">
            @forelse ($barbers as $barber)
                <x-barber-picture :barber="$barber" />
            @empty
                <x-empty-card class="col-span-3">
                    <p class="text-lg font-medium">You don't have any barbers!</p>
                    <a href="" class=" text-blue-700 hover:underline">Add a new one here!</a>
                </x-empty-card>
            @endforelse
        </div>

        <div class="flex gap-2">
            <x-link-button link="" role="ctaMain">
                Add New Barber
            </x-link-button>

            <x-link-button :link="route('admin.barbers.index')">
                Show All Barbers
            </x-link-button>
        </div>
    </x-show-card>

    <x-show-card :show="true" type="services">
        <div class="grid grid-cols-2 gap-4 mb-6">
            @forelse ($services as $service)
                <x-link-button link="" :full="true">
                    {{ $service->name }}
                </x-link-button>
            @empty
                <x-empty-card class="col-span-3">
                    <p class="text-lg font-medium">You don't have any barbers!</p>
                    <a href="" class=" text-blue-700 hover:underline">Add a new one here!</a>
                </x-empty-card>
            @endforelse
        </div>

        <div class="flex gap-2">
            <x-link-button link="" role="ctaMain">
                Add New Service
            </x-link-button>

            <x-link-button :link="route('services.index')">
                Show All Services
            </x-link-button>
        </div>
    </x-show-card>
</x-user-layout>