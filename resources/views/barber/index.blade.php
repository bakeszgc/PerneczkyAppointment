<x-user-layout currentView="admin" title="Manage Barbers - ">
    <x-breadcrumbs :links="[
        'Admin Dashboard' => route('admin'),
        'Manage Barbers' => ''
    ]"/>

    <x-headline class="mb-4">Manage Barbers</x-headline>

    <x-card>
        <div class="overflow-auto">
            <table class="mb-4 w-full">
                <tr class="*:font-bold *:p-2 bg-slate-300">
                    <td class="max-lg:hidden"></td>
                    <td>ID</td>
                    <td>Display name</td>
                    <td>Real name</td>
                    <td>Barber since</td>
                    <td>Visible</td>
                    <td>Admin</td>
                    <td></td>
                </tr>
                @forelse ($barbers as $barber)
                    <tr @class([
                        'odd:bg-slate-100 hover:bg-slate-200 *:p-2',
                        'text-slate-500' => $barber->deleted_at])>
                        <td class="max-lg:hidden"><img src="{{ $barber->getPicture() }}" alt="{{ $barber->getName() }}" class="h-16 rounded-md"></td>
                        <td class="text-center">{{ $barber->id }}</td>
                        <td>{{ $barber->getName() }} {{ $barber->isDeleted() }}</td>
                        <td>{{ $barber->user->first_name . " " . $barber->user->last_name }}</td>
                        <td>{{ $barber->created_at }}</td>
                        <td class="text-center">
                            <input type="checkbox" {{ $barber->is_visible ? 'checked' : '' }}>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" {{ $barber->user->is_admin ? 'checked=' : '' }}>
                        </td>
                        <td>
                            <x-link-button role="show" link="{{ route('barbers.show',$barber) }}">
                                Details
                            </x-button>
                        </td>
                    </tr>
                @empty

                @endforelse
            </table>
        </div>

        <div>
            <x-link-button link="{{ route('barbers.create') }}" role="createMain">
                New barber
            </x-link-button>
        </div>
    </x-card>
</x-user-layout>