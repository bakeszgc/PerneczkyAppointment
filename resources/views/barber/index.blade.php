<x-user-layout currentView="admin" title="Manage barbers">
    <x-breadcrumbs :links="[
        'Admin dashboard' => route('admin'),
        'Barbers' => ''
    ]"/>

    <x-headline class="mb-4">Manage barbers</x-headline>

    <x-card class="mb-4">
        <div class="overflow-auto">
            <table class="mb-4 w-full">
                <tr class="*:font-bold *:p-2 bg-slate-300">
                    <td class="max-lg:hidden"></td>
                    <td>ID</td>
                    <td>Display name</td>
                    <td>Real name</td>
                    <td class="max-md:hidden">Barber since</td>
                    <td class="max-md:hidden">Visible</td>
                    <td class="max-md:hidden">Admin</td>
                    <td></td>
                </tr>
                @forelse ($barbers as $barber)
                    <tr @class([
                        'odd:bg-slate-100 hover:bg-slate-200 max-sm:text-xs *:p-2',
                        'text-slate-500' => $barber->deleted_at])>
                        <td class="max-lg:hidden"><img src="{{ $barber->getPicture() }}" alt="{{ $barber->getName() }}" class="h-16 rounded-md"></td>
                        <td class="text-center">{{ $barber->id }}</td>
                        <td>{{ $barber->getName() }} {{ $barber->isDeleted() }}</td>
                        <td>{{ $barber->user->first_name . " " . $barber->user->last_name }}</td>
                        <td class="max-md:hidden">{{ $barber->created_at }}</td>
                        <td class="text-center max-md:hidden">
                            <x-input-field type="checkbox" name="isVisible" id="isVisibleCheckBox_{{ $barber->id }}" :checked="$barber->is_visible" :readonly="true" />
                        </td>
                        <td class="text-center max-md:hidden">
                            <x-input-field type="checkbox" name="isAdmin" id="isAdminCheckBox_{{ $barber->id }}"  :checked="$barber->user->is_admin" :readonly="true" />
                        </td>
                        <td>
                            <x-link-button role="show" link="{{ route('barbers.show',$barber) }}">
                                <span class="max-md:hidden">Details</span>
                            </x-link-button>
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