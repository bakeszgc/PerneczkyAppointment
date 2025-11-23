<x-user-layout currentView="admin" title="{{ __('admin.manage_services') }}">

    <div class="flex justify-between items-end align-bottom mb-4">
        <div>
            <x-breadcrumbs :links="[
                __('home.admin_dashboard') => route('admin'),
                __('admin.services') => ''
            ]"/>
            <x-headline>{{ __('admin.manage_services') }}</x-headline>
        </div>
        <div>
            <x-link-button :link="route('services.create')" role="createMain">
                <span class="max-sm:hidden">
                    {{ __('admin.new_service') }}
                </span>
            </x-link-button>
        </div>
    </div>
    
    <x-card class="mb-4">
        <div class=" overflow-x-auto">
            <table class="w-full">
                <tr class="*:font-bold *:p-2 bg-slate-300">
                    <td>ID</td>
                    <td>{{ __('admin.name') }}</td>
                    <td class="text-center">{{ __('admin.price') }}</td>
                    <td class="text-center">{{ __('admin.duration') }}</td>
                    <td class="text-center max-md:hidden">{{ __('home.bookings') }}</td>
                    <td class="max-md:hidden">{{ __('admin.visible') }}</td>
                    <td></td>
                </tr>
                @forelse ($services as $service)
                    <tr @class([
                        'odd:bg-slate-100 hover:bg-slate-200 max-sm:text-xs *:p-2',
                        'text-slate-500' => $service->deleted_at
                        ])>
                        <td>{{ $service->id }}</td>
                        <td>{{ $service->getName() . ' ' . $service->isDeleted() }}</td>
                        <td class="text-center">
                            {!! number_format($service->price,thousands_separator:"&nbsp;") !!}&nbsp;HUF
                        </td>
                        <td class="text-center">
                            {!! $service->duration . "&nbsp;" . __('home.minutes') !!}
                        </td>
                        <td class="text-center max-md:hidden">
                            {!! number_format($service->appointments_count,thousands_separator:"&nbsp;") !!}
                        </td>
                        <td class="text-center max-md:hidden">
                            <x-input-field type="checkbox" name="is_visible" id="is_visible_{{ $service->id }}" :checked="$service->is_visible" value="is_visible"></x-input-field>
                        </td>
                        <td>
                            <x-link-button :link="route('services.show',$service)" role="show">
                                <span class="max-md:hidden">{{ __('appointments.details') }}</span>
                            </x-link-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <x-empty-card class="mt-4">
                                <p class="text-lg max-md:text-base font-medium">
                                    {{ __('admin.no_services') }}
                                </p>
                                <a href="{{ route('services.create') }}" class=" text-blue-700 hover:underline">
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