<x-user-layout currentView="admin" title="Manage services">

    <div class="flex justify-between items-end align-bottom mb-4">
        <div>
            <x-breadcrumbs :links="[
                'Admin dashboard' => route('admin'),
                'Services' => ''
            ]"/>
            <x-headline>Manage services</x-headline>
        </div>
        <div>
            <x-link-button :link="route('services.create')" role="createMain">
                <span class="max-sm:hidden">New service</span>
            </x-link-button>
        </div>
    </div>
    
    <x-card class="mb-4">
        <div class=" overflow-x-auto">
            <table class="w-full">
                <tr class="*:font-bold *:p-2 bg-slate-300">
                    <td>ID</td>
                    <td>Name</td>
                    <td class="text-center">Price</td>
                    <td class="text-center">Duration</td>
                    <td class="text-center max-md:hidden">Bookings</td>
                    <td class="max-md:hidden">Visible</td>
                    <td></td>
                </tr>
                @forelse ($services as $service)
                    <tr @class([
                        'odd:bg-slate-100 hover:bg-slate-200 max-sm:text-xs *:p-2',
                        'text-slate-500' => $service->deleted_at
                        ])>
                        <td>{{ $service->id }}</td>
                        <td>{{ $service->name }} {{ $service->isDeleted() }}</td>
                        <td class="text-center">{{ number_format($service->price,thousands_separator:" ") }} HUF</td>
                        <td class="text-center">{{ $service->duration }}&nbsp;minutes</td>
                        <td class="text-center max-md:hidden">{{ number_format($service->appointments_count,thousands_separator:" ") }}</td>
                        <td class="text-center max-md:hidden">
                            <x-input-field type="checkbox" name="is_visible" id="is_visible_{{ $service->id }}" :checked="$service->is_visible" value="is_visible"></x-input-field>
                        </td>
                        <td>
                            <x-link-button :link="route('services.show',$service)" role="show">
                                <span class="max-md:hidden">Details</span>
                            </x-link-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <x-empty-card class="mt-4">
                                <p class="text-lg font-medium">You don't have any services yet!</p>
                                <a href="{{ route('services.create') }}" class=" text-blue-700 hover:underline">Add a new one here!</a>
                            </x-empty-card>
                        </td>
                    </tr>

                @endforelse
            </table>
        </div>
    </x-card>
</x-user-layout>