<x-user-layout currentView="admin" title="Admin Dashboard">
    <x-breadcrumbs :links="[
        'Admin Dashboard' => ''
    ]"/>

    <x-headline class="mb-4">Admin Dashboard</x-headline>

    <x-show-card :show="true" type="bookings" class="mb-6">
        <x-sum-of-bookings class="mb-8" :sumOfBookings="$sumOfBookings" context="bookings" />

        <div class="flex gap-2">
            <x-link-button :link="route('bookings.index')" role="ctaMain">
                All bookings
            </x-link-button>

            <x-link-button :link="route('bookings.create')" role="create">
                New booking
            </x-link-button>
        </div>
    </x-show-card>

    <x-show-card :show="true" type="barbers" class="mb-6">
        <div class="grid grid-cols-3 gap-6 mb-6">
            @forelse ($barbers as $barber)
                <a href="{{ route('barbers.show',$barber) }}">
                    <x-barber-picture :barber="$barber" />
                </a>
            @empty
                <x-empty-card class="col-span-3">
                    <p class="text-lg font-medium">You don't have any barbers!</p>
                    <a href="{{ route('barbers.create') }}" class=" text-blue-700 hover:underline">Add a new one here!</a>
                </x-empty-card>
            @endforelse
        </div>

        <div class="flex gap-2">
            <x-link-button :link="route('barbers.index')" role="ctaMain">
                All barbers
            </x-link-button>

            <x-link-button :link="route('barbers.create')" role="create">
                New barber
            </x-link-button>
        </div>
    </x-show-card>

    <x-show-card :show="true" type="timeoff" class="mb-6">
        <x-sum-of-bookings class="mb-8" :sumOfBookings="$sumOfTimeOffs" context="time offs" />

        <div class="flex gap-2">
            <x-link-button :link="route('admin-time-offs.index')" role="timeoffMain">
                All time offs
            </x-link-button>

            <x-link-button :link="route('admin-time-offs.create')" role="timeoffCreate">
                New time off
            </x-link-button>
        </div>
    </x-show-card>

    <x-show-card :show="true" type="services" class="mb-6">
        <div class="grid grid-cols-2 gap-4 mb-6">
            @forelse ($services as $service)
                <x-link-button link="{{ route('services.show',$service) }}" :full="true">
                    {{ $service->name }}
                </x-link-button>
            @empty
                <x-empty-card class="col-span-3">
                    <p class="text-lg font-medium">You don't have any services!</p>
                    <a href="{{ route('services.create') }}" class=" text-blue-700 hover:underline">Add a new one here!</a>
                </x-empty-card>
            @endforelse
        </div>

        <div class="flex gap-2">
            <x-link-button :link="route('services.index')" role="ctaMain">
                All services
            </x-link-button>

            <x-link-button :link="route('services.create')" role="create">
                Create service
            </x-link-button>
        </div>
    </x-show-card>

    <x-show-card :show="true" type="customers" class="mb-6">
            <div class=" overflow-x-auto mb-4">
            <table class="w-full">
                <tr class="*:font-bold *:p-2 bg-slate-300">
                    <td>Name</td>
                    <td>Email</td>
                    <td>Bookings</td>
                    <td>Barber</td>
                    <td>Admin</td>
                    <td></td>
                </tr>
                @forelse ($users as $user)
                    <tr @class([
                        'odd:bg-slate-100 hover:bg-slate-200 *:p-2',
                        'text-slate-500' => $user->deleted_at
                        ])>
                        <td>{{ $user->first_name . " " . $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="text-center">
                            {{ $user->appointments_count }}
                        </td>
                        <td class="text-center">
                            <x-input-field type="checkbox" name="is_barber" id="{{ 'is_barber_'.$user->id }}" :checked="isset($user->barber) && $user->barber->deleted_at == null" value="is_barber" />
                        </td>
                        <td class="text-center">
                            <x-input-field type="checkbox" name="is_admin" id="{{ 'is_admin_'.$user->id }}" :checked="$user->is_admin" value="is_admin" />
                        </td>
                        <td>
                            <x-link-button :link="route('customers.show',$user)" role="show">
                                Details
                            </x-link-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <x-empty-card class="mt-4">
                                <p>You don't have any customers yet!</p>
                            </x-empty-card>
                        </td>
                    </tr>

                @endforelse
            </table>
        </div>

        <div class="flex gap-2">
            <x-link-button :link="route('customers.index')" role="ctaMain">
                All customers
            </x-link-button>
        </div>
    </x-show-card>
</x-user-layout>