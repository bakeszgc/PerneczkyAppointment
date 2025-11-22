<x-user-layout currentView="admin" title="{{ __('admin.manage_barbers') }}">

    <div class="flex justify-between items-end align-bottom mb-4">
        <div>
            <x-breadcrumbs :links="[
                __('home.admin_dashboard') => route('admin'),
                __('admin.barbers') => ''
            ]"/>
            <x-headline>{{ __('admin.manage_barbers') }}</x-headline>
        </div>
        <div>
            <x-link-button :link="route('barbers.create')" role="createMain">
                <span class="max-sm:hidden">{{ __('admin.new_barber') }}</span>
            </x-link-button>
        </div>
    </div>

    <x-card class="mb-4">
        <div class="overflow-auto">
            <table class=" w-full">
                <tr class="*:font-bold *:p-2 bg-slate-300">
                    <td class="max-lg:hidden"></td>
                    <td>ID</td>
                    <td>{{ __('users.display_name') }}</td>
                    <td>{{ __('admin.full_name') }}</td>
                    <td class="max-md:hidden">{{ __('admin.barber_since') }}</td>
                    <td class="max-md:hidden">{{ __('admin.visible') }}</td>
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
                                <span class="max-md:hidden">{{ __('appointments.details') }}</span>
                            </x-link-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <x-empty-card class="mt-4">
                                <p class="text-lg max-md:text-base font-medium">{{ __('admin.no_barbers') }}</p>
                                <a href="{{ route('barbers.create') }}" class=" text-blue-700 hover:underline">
                                    {{ __('admin.new_one') }}
                                </a>
                            </x-empty-card>
                        </td>
                    </tr>
                @endforelse
            </table>
        </div>
    </x-card>
</x-user-layout>