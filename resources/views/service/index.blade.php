<x-user-layout currentView="admin" title="Manage Services - ">
    <x-breadcrumbs :links="[
        'Admin Dashboard' => route('admin'),
        'Manage Services' => ''
    ]"/>

    <x-headline class="mb-4">Manage Services</x-headline>

    <x-card>
        <table class="mb-4 w-full">
            <tr class="*:font-bold *:p-2 bg-slate-300">
                <td>ID</td>
                <td>Service name</td>
                <td>Current price</td>
                <td>Duration</td>
                <td>Time of creation</td>
                <td>Visible</td>
                <td></td>
            </tr>
            @forelse ($services as $service)
                <tr class="odd:bg-slate-100 hover:bg-slate-200 *:p-2">
                    <td>{{ $service->id }}</td>
                    <td>{{ $service->name }}</td>
                    <td>{{ number_format($service->price,thousands_separator:" ") }} Ft</td>
                    <td>{{ $service->duration }}&nbsp;minutes</td>
                    <td>{{ $service->created_at }}</td>
                    <td>
                        <input type="checkbox" name="" id="">
                    </td>
                    <td>
                        <x-link-button :link="route('services.show',$service)" role="show">
                            Details
                        </x-link-button>
                    </td>
                </tr>
            @empty

            @endforelse
        </table>

        <div>
            <x-link-button link="" role="ctaMain">
                Add New Service
            </x-link-button>
        </div>
    </x-card>
</x-user-layout>