<x-user-layout currentView="admin" title="{{ __('home.admin_dashboard') }}">
    <x-breadcrumbs :links="[
        __('home.admin_dashboard') => ''
    ]"/>

    <x-headline class="mb-4">{{ __('home.admin_dashboard') }}</x-headline>

    <x-card class="mb-6">
        <x-appointment-calendar :calAppointments="$calAppointments" access="barber" :barber="auth()->user()?->barber ?? $allBarbers->first()" :barbers="$allBarbers" defaultView="day"/>
    </x-card>

    <x-show-card :show="true" type="bookings" class="mb-6">
        <x-sum-of-bookings class="mb-8" :sumOfBookings="$sumOfBookings" context="bookings" />

        <div class="flex gap-2">
            <x-link-button :link="route('bookings.index')" role="ctaMain">
                {{ __('admin.all_bookings') }}
            </x-link-button>

            <x-link-button :link="route('bookings.create')" role="create">
                {{ __('appointments.new_booking') }}
            </x-link-button>
        </div>
    </x-show-card>

    <x-show-card :show="true" type="barbers" class="mb-6">
        <div class="grid grid-cols-3 max-sm:grid-cols-2 gap-6 mb-6">
            @forelse ($barbers as $barber)
                <a href="{{ route('barbers.show',$barber) }}">
                    <x-barber-picture :barber="$barber" />
                </a>
            @empty
                <x-empty-card class="col-span-3 max-sm:grid-cols-2">
                    <p class="text-lg max-md:text-base font-medium">
                        {{ __('admin.no_barbers') }}
                    </p>
                    <a href="{{ route('barbers.create') }}" class=" text-blue-700 hover:underline">
                        {{ __('admin.new_one') }}
                    </a>
                </x-empty-card>
            @endforelse
        </div>

        <div class="flex gap-2">
            <x-link-button :link="route('barbers.index')" role="ctaMain">
                {{ __('admin.all_barbers') }}
            </x-link-button>

            <x-link-button :link="route('barbers.create')" role="create">
                {{ __('admin.new_barber') }}
            </x-link-button>
        </div>
    </x-show-card>

    <x-show-card :show="true" type="timeoff" class="mb-6">
        <x-sum-of-bookings class="mb-8" :sumOfBookings="$sumOfTimeOffs" context="time_offs" />

        <div class="flex gap-2">
            <x-link-button :link="route('admin-time-offs.index')" role="timeoffMain">
                {{ __('admin.all_timeoffs') }}
            </x-link-button>

            <x-link-button :link="route('admin-time-offs.create')" role="timeoffCreate">
                {{ __('admin.new_timeoff') }}
            </x-link-button>
        </div>
    </x-show-card>

    <x-show-card :show="true" type="services" class="mb-6">
        <div class="overflow-x-auto mb-4">
            <table class="w-full">
                <tr class="*:font-bold *:p-2 bg-slate-300">
                    <td>{{ __('admin.name') }}</td>
                    <td class="text-center">{{ __('appointments.price') }}</td>
                    <td class="text-center">{{ __('admin.duration') }}</td>
                    <td class="text-center max-md:hidden">{{ __('home.bookings') }}</td>
                    <td class="text-center max-md:hidden">{{ __('admin.visible') }}</td>
                    <td></td>
                </tr>
                @forelse ($services as $service)
                    <tr @class([
                        'odd:bg-slate-100 hover:bg-slate-200 max-sm:text-xs *:p-2',
                        'text-slate-500' => $service->deleted_at
                        ])>
                        <td>{{ $service->getName() }}</td>
                        <td class="text-center">{{ number_format($service->price,thousands_separator:" ") }}&nbsp;HUF</td>
                        <td class="text-center">{{ $service->duration . ' ' . __('home.minutes') }}</td>
                        <td class="text-center max-md:hidden">
                            {{ number_format($service->appointments_count,thousands_separator:' ') }}
                        </td>
                        <td class="text-center max-md:hidden">
                            <x-input-field type="checkbox" name="is_visible" id="{{ 'is_visible_'.$service->id }}" :checked="$service->is_visible" value="is_visible" />
                        </td>
                        <td class="text-center">
                            <x-link-button :link="route('services.show',$service)" role="show">
                                <span class="max-md:hidden">{{ __('appointments.details') }}</span>
                            </x-link-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <x-empty-card class="mt-4">
                                <p class="text-lg max-md:text-base font-medium">{{ __('admin.no_services') }}</p>
                                <a href="{{ route('services.create') }}" class=" text-blue-700 hover:underline">{{ __('admin.new_one') }}</a>
                            </x-empty-card>
                        </td>
                    </tr>
                @endforelse
            </table>
        </div>

        <div class="flex gap-2">
            <x-link-button :link="route('services.index')" role="ctaMain">
                {{ __('admin.all_services') }}
            </x-link-button>

            <x-link-button :link="route('services.create')" role="create">
                {{ __('admin.new_service') }}
            </x-link-button>
        </div>
    </x-show-card>

    <x-show-card :show="true" type="customers" class="mb-6">
        <div class=" overflow-x-auto mb-4">
            <table class="w-full">
                <tr class="*:font-bold *:p-2 bg-slate-300">
                    <td>{{ __('admin.name') }}</td>
                    <td class="max-md:hidden">{{ __('auth.email') }}</td>
                    <td class="text-center">{{ __('home.bookings') }}</td>
                    <td class="text-center max-md:hidden">{{ __('appointments.barber') }}</td>
                    <td class="text-center max-md:hidden">Admin</td>
                    <td></td>
                </tr>
                @forelse ($users as $user)
                    <tr @class([
                        'odd:bg-slate-100 hover:bg-slate-200 max-sm:text-xs *:p-2',
                        'text-slate-500' => $user->deleted_at
                        ])>
                        <td>{{ $user->first_name . " " . $user->last_name }}</td>
                        <td class="max-md:hidden">{{ $user->email }}</td>
                        <td class="text-center">
                            {{ number_format($user->appointments_count,thousands_separator:' ') }}
                        </td>
                        <td class="text-center max-md:hidden">
                            <x-input-field type="checkbox" name="is_barber" id="{{ 'is_barber_'.$user->id }}" :checked="isset($user->barber) && $user->barber->deleted_at == null" value="is_barber" />
                        </td>
                        <td class="text-center max-md:hidden">
                            <x-input-field type="checkbox" name="is_admin" id="{{ 'is_admin_'.$user->id }}" :checked="$user->is_admin" value="is_admin" />
                        </td>
                        <td class="text-center">
                            <x-link-button :link="route('customers.show',$user)" role="show">
                                <span class="max-md:hidden">{{ __('appointments.details') }}</span>
                            </x-link-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <x-empty-card class="mt-4">
                                <p>{{ __('admin.no_customers') }}</p>
                            </x-empty-card>
                        </td>
                    </tr>
                @endforelse
            </table>
        </div>

        <div class="flex gap-2">
            <x-link-button :link="route('customers.index')" role="ctaMain">
                {{ __('admin.all_customers') }}
            </x-link-button>
        </div>
    </x-show-card>
</x-user-layout>