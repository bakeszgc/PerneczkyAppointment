<x-user-layout currentView="admin" :title="$barber->getName()">
    <x-breadcrumbs :links="[
        __('home.admin_dashboard') => route('admin'),
        __('admin.barbers') => route('barbers.index'),
        $barber->getName() => ''
    ]"/>

    <x-headline class="mb-4">
        <span @class(['text-slate-500' => isset($barber->deleted_at)])>
            {{ $barber->getName() . ' ' . $barber->isDeleted() }}
        </span>
    </x-headline>

    <x-show-card :show="$showCalendar" type="calendar" class="mb-4">
        <x-appointment-calendar :calAppointments="$calAppointments" :barber="$barber" access="admin" :barbers="$barbers"/>
    </x-show-card>

    <x-show-card :show="$showProfile" type="profile" class="mb-4">
        <form action="{{ route('barbers.update',$barber) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <div class="grid grid-cols-2 gap-2 mb-4">
                    <div class="flex flex-col max-sm:col-span-2">
                        <x-label for="first_name">{{ __('auth.first_name') }}*</x-label>
                        <x-input-field name="first_name" id="first_name" value="{{ old('first_name') ??$barber->user->first_name }}" :disabled="isset($barber->deleted_at) || isset($user->barber->deleted_at)" class="profileInput profileReqInput" />
                        @error('first_name')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="flex flex-col max-sm:col-span-2">
                        <x-label for="last_name">{{ __('auth.last_name') }}*</x-label>
                        <x-input-field name="last_name" id="last_name" value="{{ old('last_name') ??$barber->user->last_name }}" :disabled="isset($barber->deleted_at) || isset($user->barber->deleted_at)" class="profileInput profileReqInput"/>
                        @error('last_name')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col col-span-2">
                        <x-label for="display_name">{{ __('users.display_name') }}</x-label>
                        <x-input-field name="display_name" id="display_name" value="{{ old('display_name') ??$barber->display_name }}" :disabled="isset($barber->deleted_at) || isset($user->barber->deleted_at)" class="profileInput"/>
                        @error('display_name')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col col-span-2">
                        <x-label for="description">{{ __('users.description') }} (<span id="charCount">xxx</span>/500)</x-label>
                        <x-input-field type="textarea" name="description" id="description" :disabled="isset($barber->deleted_at) || isset($user->barber->deleted_at)" class="profileInput">{{ old('description') ?? $barber->description }}</x-input-field>
                        @error('description')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col col-span-2">
                        <div class="flex justify-between items-end">
                            <x-label for="email">{{ __('auth.email') }}*</x-label>
                            @if ($barber->user->email_verified_at === null)
                                <p class="text-slate-500 text-sm">{{ __('users.not_verified_yet') }}</p>
                            @else
                                <p class="text-slate-500 text-sm">{{ __('users.verified_on') .' '. date_format($barber->user->email_verified_at,'Y-m-d')  }}</p>
                            @endif
                            
                        </div>
                        
                        <x-input-field type="email" name="email" id="email" value="{{ old('email') ?? $barber->user->email }}" :disabled="isset($barber->deleted_at) || isset($user->barber->deleted_at)" class="profileInput profileReqInput"/>
                        @error('email')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col max-sm:col-span-2">
                        <x-label for="date_of_birth">{{ __('auth.date_of_birth') }}</x-label>
                        <x-input-field type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') ?? $barber->user->date_of_birth }}" :disabled="isset($barber->deleted_at) || isset($user->barber->deleted_at)" class="profileInput"/>
                        @error('date_of_birth')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col max-sm:col-span-2">
                        <x-label for="telephone_number">{{ __('auth.tel_number') }}</x-label>
                        <x-input-field type="tel" name="telephone_number" id="telephone_number" value="{{ old('telephone_number') ?? $barber->user->tel_number }}" :disabled="isset($barber->deleted_at) || isset($user->barber->deleted_at)" class="profileInput"/>
                        @error('telephone_number')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center gap-2">
                        <x-input-field type="checkbox" name="is_visible" id="is_visible" :checked="$barber->is_visible" value="is_visible" :disabled="isset($barber->deleted_at) || isset($user->barber->deleted_at)" class="profileInput" />
                        <label for="is_visible">
                            {{ __('admin.visible_for_everyone') }}
                        </label>
                        @error('is_visible')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="text-right">
                        * {{ __('auth.required_fields') }}
                    </div>
                </div>
            </div>

            <div class="border-2 border-dashed rounded-md p-4 border-yellow-400 mb-4">
                <h3 class="text-xl max-md:text-lg mb-2">{{ __('admin.attention') }}</h3>
                <p class="max-sm:text-xs">
                    {{ __('admin.viewing_barber_page_1') . $barber->user->first_name . __('admin.viewing_barber_page_2') }}

                    <a href="{{ route('customers.show',$barber->user) }}" class="text-blue-700 hover:underline">{{ __('admin.viewing_barber_page_3') }}</a>!
                </p>
            </div>
            
            <div class="flex max-sm:flex-col gap-2">
                <div class="max-sm:w-full">
                    <x-button role="ctaMain" :disabled="isset($barber->deleted_at)" :full="true" id="profileButton" :disabled="true">{{ __('users.save_changes') }}</x-button>
                    </form>
                </div>

                <div class="max-sm:w-full">
                    @if ($barber->deleted_at)
                        <form action="{{ route('barbers.restore',$barber) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <x-button role="restore" :full="true">{{ __('admin.restore_barber_access') }}</x-button>
                        </form>
                    @else
                        <form action="{{ route('barbers.destroy',$barber) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <x-button role="destroy" :full="true">{{ __('admin.remove_barber_access') }}</x-button>
                        </form>
                    @endif
                </div>
            </div>
        
    </x-show-card>

    <x-show-card :show="$showPicture" type="picture" class="mb-4">
        <form action="{{ route('upload-cropped-admin',$barber->user) }}" name="pictureForm" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="source" value="admin">
            <input type="file" id="selectedImg" class="form-control" accept="image/*" hidden>
            <input type="file" id="croppedImg" name="croppedImg" class="form-control" hidden>
            <input type="hidden" name="lang" id="langCheck" value="{{ App::getLocale() }}">

            <div class="flex gap-8 max-sm:flex-col">
                <div class="min-w-60">
                    <h3 class="font-bold text-lg mb-2" id="currentPfpTitle">
                        {{ __('users.current_pfp') }}
                    </h3>
                    <div class="relative w-fit group cursor-pointer  rounded-md border border-slate-500">
                        <img src="{{ $barber->getPicture() }}" alt="Profile picture" id="currentPfp" class="w-60  group-hover:blur-sm transition-all rounded-md max-sm:w-full">
                        <label for="selectedImg" class="cursor-pointer">
                            <div class="absolute w-full h-full top-0 preview overflow-hidden"></div>
                            <div class="absolute w-full h-full top-0 flex items-center justify-center group-hover:bg-black group-hover:bg-opacity-75 transition-all rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"stroke-width="1.5" stroke="white" class="size-6 opacity-0 group-hover:opacity-100 transition-all cursor-pointer z-20">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                </svg>
                            </div>
                        </label>
                    </div>
                    @error('selectedImg')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                    @error('croppedImg')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>

                <div>
                    <h3 class="font-bold text-lg mb-2">
                        {{ __('users.guidelines') }}
                    </h3>

                    <p class="mb-2 text-justify">
                        {{ __('users.guidelines_p1') }}
                        <span class="sm:hidden">{{ __('users.above') }}</span>
                        <span class="max-sm:hidden">{{ __('users.left') }}</span>
                        {{ __('users.guidelines_p2') }}
                    </p>

                    <ul class="list-disc *:ml-6 *:mb-1 mb-4">
                        <li>
                            {{ __('users.guidelines_bp1') }}
                        </li>
                        <li>
                            {{ __('users.guidelines_bp2') }}
                        </li>
                        <li>
                            {{ __('users.guidelines_bp3') }}
                        </li>
                        <li>
                            {{ __('users.guidelines_bp4') }}
                        </li>
                        <li>
                            {{ __('users.guidelines_bp5') }}
                        </li>
                        <li>
                            {{ __('users.guidelines_bp6') }}
                        </li>
                    </ul>
                    
                    <div id="submitDiv" hidden>
                        <x-button id="submit" role="ctaMain">
                            {{ __('users.save_changes') }}
                        </x-button>
                    </div>
                </div>
            </div>
        </form>
    </x-show-card>

    <x-modal id="cropModal">
        <div class="flex justify-between items-center w-full mb-4">
            <h1 class="text-xl max-md:text-lg font-bold">
                {{ __('users.crop_your_photo') }}
            </h1>
            <div id="closeModal" class="hover:bg-blue-100 transition-all rounded-full p-2 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>
        </div>

        <div class="overflow-hidden">
            <img src="" alt="" id="image" class="max-w-xl max-h-[60vh]">
        </div>
        
        <div class="flex gap-2 mt-4">
            <x-button id="crop" :hidden="true" role="ctaMain">
                {{ __('users.crop') }}
            </x-button>
            <x-button id="reset" :hidden="true">
                {{ __('users.reset') }}
            </x-button>
        </div>
    </x-modal>
    
    <x-show-card :show="$showBookings" type="bookings" class="mb-4">
        <x-sum-of-bookings class="mb-8" :sumOfBookings="$sumOfBookings" :barber="$barber" context="bookings" />

        <div class="flex gap-2">
            <x-link-button :link="route('bookings.index',['barber' => $barber->id])" role="ctaMain">
                {{ __('admin.all_bookings') }}
            </x-link-button>

            <x-link-button :link="route('bookings.create')" role="create">
                {{ __('admin.new_booking') }}
            </x-link-button>
        </div>
    </x-show-card>

    <x-show-card :show="$showTimeOffs" type="timeoff" class="mb-4">
        <x-sum-of-bookings class="mb-8" :sumOfBookings="$sumOfTimeOffs" :barber="$barber" context="time_offs" />

        <div class="flex gap-2">
            <x-link-button :link="route('admin-time-offs.index',['barber' => $barber->id])" role="timeoffMain">
                {{ __('admin.all_timeoffs') }}
            </x-link-button>

            <x-link-button :link="route('admin-time-offs.create',['barber' => $barber->id])" role="timeoffCreate">
                {{ __('admin.new_timeoff') }}
            </x-link-button>
        </div>
    </x-show-card>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // CHARACTER COUNTER
            const charCount = document.getElementById('charCount');
            const description = document.getElementById('description');
            if (description && charCount) {
                countCharacters(charCount, description);
            }

            // PROFILE BUTTON RE-ENABLE
            const profileButton = document.getElementById('profileButton');
            const profileInputs = document.querySelectorAll('.profileInput');
            const profileReqInputs = document.querySelectorAll('.profileReqInput');

            enableButtonIfInputsFilled(profileButton, profileInputs, profileReqInputs);
        });
    </script>
</x-user-layout>