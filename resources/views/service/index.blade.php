<x-user-layout currentView="admin" title="Manage Services">
    <x-breadcrumbs :links="[
        'Admin Dashboard' => route('admin'),
        'Manage Services' => ''
    ]"/>

    <x-headline class="mb-4">Manage Services</x-headline>

    <x-card>
        <div class=" overflow-x-auto mb-4">
            <table class="w-full">
                <tr class="*:font-bold *:p-2 bg-slate-300">
                    <td>ID</td>
                    <td>Service&nbsp;name</td>
                    <td>Current&nbsp;price</td>
                    <td>Duration</td>
                    <td>Time&nbsp;of&nbsp;creation</td>
                    <td>Visible</td>
                    <td></td>
                </tr>
                @forelse ($services as $service)
                    <tr @class([
                        'odd:bg-slate-100 hover:bg-slate-200 *:p-2',
                        'text-slate-500' => $service->deleted_at
                        ])>
                        <td>{{ $service->id }}</td>
                        <td>{{ $service->name }} {{ $service->isDeleted() }}</td>
                        <td>{{ number_format($service->price,thousands_separator:" ") }} HUF</td>
                        <td>{{ $service->duration }}&nbsp;minutes</td>
                        <td>{{ $service->created_at }}</td>
                        <td class="text-center">
                            <x-input-field type="checkbox" name="is_visible" id="is_visible" :checked="$service->is_visible" value="is_visible"></x-input-field>
                        </td>
                        <td>
                            <x-link-button :link="route('services.show',$service)" role="show">
                                Details
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

        <div>
            <x-link-button :link="route('services.create')" role="ctaMain">
                Add New Service
            </x-link-button>
        </div>
    </x-card>
</x-user-layout>