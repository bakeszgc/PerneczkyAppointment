@use('App\Models\Appointment')

<x-user-layout currentView="admin" :title="$service->getName()">

    <div class="flex justify-between items-end align-bottom mb-4">
        <div>
            <x-breadcrumbs :links="[
                __('home.admin_dashboard') => route('admin'),
                __('admin.services') => route('services.index'),
                $service->getName() => ''
            ]"/>

            <x-headline>
                <span @class(['text-slate-500' => isset($service->deleted_at)])>
                    {{ $service->getName() . ' ' . $service->isDeleted() }}
                </span>
            </x-headline>
        </div>
        <div>
            <x-link-button :link="route('services.create')" role="createMain">
                <span class="max-sm:hidden">
                    {!! __('admin.new_service') !!}
                </span>
            </x-link-button>
        </div>
    </div>
    

    <x-card class="mb-6">
        <form action="{{ route('services.update',$service) }}" method="POST">
            @method('PUT')
            @csrf

            <div class="flex flex-col mb-4">
                <x-label for="name">
                    {{ __('admin.name_hu') }}*
                </x-label>
                <x-input-field id="name_hu" name="name_hu" :value="old('name_hu') ?? $service->name" :disabled="isset($service->deleted_at)" class="updateInput reqInput"></x-input-field>
                @error('name_hu')
                    <p class=" text-red-500">{{$message}}</p>
                @enderror
            </div>

            <div class="flex flex-col mb-4">
                <x-label for="name">
                    {{ __('admin.name_en') }}*
                </x-label>
                <x-input-field id="name_en" name="name_en" :value="old('name_en') ?? $service->en_name" :disabled="isset($service->deleted_at)" class="updateInput reqInput"></x-input-field>
                @error('name_en')
                    <p class=" text-red-500">{{$message}}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 max-md:grid-cols-1 gap-4 mb-4">
                <div class="flex flex-col">
                    <x-label for="price">
                        {{ __('barber.price_in_huf') }}*
                    </x-label>
                    <x-input-field type="number" id="price" name="price" :value="old('price') ?? $service->price" :disabled="isset($service->deleted_at)" class="updateInput reqInput"></x-input-field>
                    @error('price')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>

                <div class="flex flex-col">
                    <x-label for="duration">
                        {{ __('admin.duration_in_minutes') }}*
                    </x-label>
                    <x-input-field type="number" id="duration" name="duration" :value="old('duration') ?? $service->duration" :disabled="isset($service->deleted_at)" class="updateInput reqInput"></x-input-field>
                    @error('duration')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4 flex justify-between items-center">
                <div class="flex gap-2 items-center">
                    <x-input-field type="checkbox" name="is_visible" id="is_visible" :checked="$service->is_visible" value="is_visible" :disabled="isset($service->deleted_at)" class="updateInput"></x-input-field>
                    <label for="is_visible">
                        {{ __('admin.visible_for_everyone') }}
                    </label>
                    @error('is_visible')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>
                <div>
                    <p>* {{ __('auth.required_fields') }}</p>
                </div>
            </div>
            

            <div class="flex gap-2">
                <x-button role="ctaMain" :disabled="true" id="updateButton">
                    {{ __('users.save_changes') }}
                </x-button>
                </form>

                @if ($service->deleted_at)
                    <form action="{{ route('services.restore',$service) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <x-button role="restore">
                            {{ __('admin.restore_service') }}
                        </x-button>
                    </form>
                @else
                    <form action="{{ route('services.destroy',$service) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <x-button role="destroy">
                            {{ __('admin.delete_service') }}
                        </x-button>
                    </form>
                @endif
            </div>
        
    </x-card>

    <h2 class="font-bold text-2xl max-md:text-xl mb-4">
        {{ __('admin.details_about_this_service') }}
    </h2>

    <x-card class="mb-4">
        <div class="mb-4 border-b-2 pb-2">
            <h3 class="text-xl max-md:text-lg font-bold mb-4">
                {{ __('admin.general_details') }}
            </h3>
            <ul class="*:mb-2">
                <li>{{ __('admin.created_at') . ": " . $service->created_at }}</li>
                <li>{{ __('admin.updated_at') . ": " . $service->updated_at }}</li>
            </ul>
        </div>
        
        <div class="flex max-md:flex-col justify-between gap-4">
            <div class="flex-1">
                <h3 class="text-xl max-md:text-lg font-bold mb-4">
                    {{ __('admin.prev_statistics') }}
                </h3>
                <ul class="*:mb-2">
                    <li>
                        {!! __('admin.booked') . ": " . Appointment::getTimesSuffix($previousStats['numBookings']) !!}
                    </li>
                    <li>
                        {!! __('admin.average_price') . ": " . number_format($previousStats['avgPrice'],$previousStats['avgPrice'] != floor($previousStats['avgPrice']) ? 2 : 0, thousands_separator:"&nbsp;") !!} HUF
                    </li>
                    <li>
                        {!! __('admin.rev_from_service') . ": " . number_format($previousStats['sumPrice'], $previousStats['sumPrice'] != floor($previousStats['sumPrice']) ? 2 : 0,thousands_separator:"&nbsp;") !!} HUF
                    </li>
                    <li>
                        <a href="{{ route('bookings.index',['service' => $service->id, 'time_window' => 'previous', 'cancelled' => 1]) }}" class="text-blue-700 hover:underline font-bold">
                            {{ __('admin.show_bookings') }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="md:border-l-2 max-md:border-b-2"></div>
            <div class="flex-1">
                <h3 class="text-xl max-md:text-lg font-bold mb-4">
                    {{ __('admin.upcoming_statistics') }}
                </h3>
                <ul class="*:mb-2">
                    <li>
                        {!! __('admin.booked') . ": " . Appointment::getTimesSuffix($upcomingStats['numBookings']) !!}
                    </li>
                    <li>
                        {!! __('admin.average_price') . ": " . number_format($upcomingStats['avgPrice'],$upcomingStats['avgPrice'] != floor($upcomingStats['avgPrice']) ? 2 : 0, thousands_separator:"&nbsp;") !!} HUF
                    </li>
                    <li>
                        {!! __('admin.rev_est_from_service') .": ". number_format($upcomingStats['sumPrice'],  $upcomingStats['sumPrice'] != floor($upcomingStats['sumPrice']) ? 2 : 0,thousands_separator:"&nbsp;") !!} HUF
                    </li>
                    <li>
                        <a href="{{ route('bookings.index',['service' => $service->id, 'time_window' => 'upcoming', 'cancelled' => 1]) }}" class="text-blue-700 hover:underline font-bold">
                            {{ __('admin.show_bookings') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </x-card>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const updateButton = document.getElementById('updateButton');
            const inputs = document.querySelectorAll('.updateInput');
            const reqInputs = document.querySelectorAll('.reqInput');
            
            enableButtonIfInputsFilled(updateButton,inputs,reqInputs);
        });
    </script>
</x-user-layout>