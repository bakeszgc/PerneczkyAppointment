@php
    $view = $view ?? 'user';

    if($view == 'admin') {
        $title = $user->getFullName();
        $updateRoute = route('customers.update',$user);
        $breadcrumbLinks = [
            __('home.admin_dashboard') => route('admin'),
            __('users.customers') => route('customers.index'),
            $title => ''
        ];
    } else {
        $title = __('home.account_settings');
        $updateRoute = route('users.update',$user);
        $breadcrumbLinks = [
            $title => ''
        ];
    }
@endphp

<x-user-layout title="{{ $title }}" currentView="{{ $view }}">
    <x-breadcrumbs :links="$breadcrumbLinks" />

    <x-headline class="mb-4">
        <span @class(['text-slate-500' => isset($user->deleted_at)])>
            {{ $title . ' ' . $user->isDeleted() }}
        </span>
    </x-headline>

    @if ($user->hasEmail())
        <x-show-card :show="$showProfile" type="profile" class="mb-4">
            <form action="{{ $updateRoute }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <div class="grid grid-cols-2 gap-2">
                        <div class="flex flex-col max-sm:col-span-2">
                            <x-label for="first_name">
                                {{ __('auth.first_name') }}*
                            </x-label>
                            <x-input-field name="first_name" id="first_name" value="{{ old('first_name') ??$user->first_name }}" :disabled="isset($user->deleted_at) || !$user->isRegistered()" class="profileInput profileReqInput" />
                            @error('first_name')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col max-sm:col-span-2">
                            <x-label for="last_name">
                                {{ __('auth.last_name') }}*
                            </x-label>
                            <x-input-field name="last_name" id="last_name" value="{{ old('last_name') ??$user->last_name }}" :disabled="isset($user->deleted_at) || !$user->isRegistered()" class="profileInput profileReqInput" />
                            @error('last_name')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror
                        </div>

                        @if ($user->barber && !isset($user->barber->deleted_at) && $view != 'admin')
                            <div class="flex flex-col col-span-2">
                                <x-label for="display_name">
                                    {{ __('users.display_name') }}
                                </x-label>
                                <x-input-field name="display_name" id="display_name" value="{{ old('display_name') ??$user->barber->display_name }}" :disabled="isset($user->deleted_at)" class="profileInput" />
                                @error('display_name')
                                    <p class=" text-red-500">{{$message}}</p>
                                @enderror
                            </div>

                            <div class="flex flex-col col-span-2">
                                <x-label for="description">
                                    {{ __('users.description') }} (<span id="charCount">xxx</span>/500)
                                </x-label>
                                <x-input-field type="textarea" name="description" id="description" :disabled="isset($user->deleted_at) || isset($user->barber->deleted_at)" class="profileInput">{{ old('comment') ?? $user->barber->description }}</x-input-field>
                                @error('description')
                                    <p class=" text-red-500">{{$message}}</p>
                                @enderror
                            </div>
                        @endif

                        <div class="flex flex-col col-span-2">
                            <div class="flex justify-between items-end">
                                <x-label for="email">
                                    {{ __('auth.email') }}*
                                </x-label>
                                <div class="text-right">
                                    @if ($user->email_verified_at === null)
                                        @if ($view == 'admin')
                                            <p class="text-slate-500 text-sm">
                                                {{ __('users.not_verified_yet') }}
                                            </p>
                                        @else
                                            <a href="{{ route('verification.notice') }}"class="text-base max-md:text-xs text-blue-500 hover:underline">
                                                {{ __('users.verify_your_email') }}
                                            </a>
                                        @endif
                                    @else
                                        <p class="text-slate-500 text-sm max-md:text-xs">
                                            {{ __('users.verified_on') . ' ' . date_format($user->email_verified_at,'Y-m-d')  }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            
                            <x-input-field type="email" name="email" id="email" value="{{ old('email') ?? $user->email }}" :disabled="isset($user->deleted_at) || !$user->isRegistered()" class="profileInput profileReqInput" />
                            @error('email')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col max-sm:col-span-2">
                            <x-label for="date_of_birth">
                                {{ __('auth.date_of_birth') }}
                            </x-label>
                            <x-input-field type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') ?? $user->date_of_birth }}" :disabled="isset($user->deleted_at) || !$user->isRegistered()" class="profileInput w-full" />
                            @error('date_of_birth')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col max-sm:col-span-2">
                            <x-label for="telephone_number">
                                {{ __('auth.tel_number') }}
                            </x-label>
                            <x-input-field type="tel" name="telephone_number" id="telephone_number" value="{{ old('telephone_number') ?? $user->tel_number }}" :disabled="isset($user->deleted_at) || !$user->isRegistered()" class="profileInput" />
                            @error('telephone_number')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4 flex justify-between">                
                    <div class="*:flex *:items-center *:gap-2">
                        @if ($view == 'admin' && $user->isRegistered())
                            <div class="mb-2">
                                <x-input-field type="checkbox" name="is_barber" id="is_barber" value="1" :checked="$user->barber && $user->barber->deleted_at == null" :disabled="isset($user->deleted_at)" class="profileInput" />
                                <label for="is_barber">{{ __('admin.barber_access') }}</label>
                            </div>

                            <div>
                                <x-input-field type="checkbox" name="is_admin" id="is_admin" value="1" :checked="$user->is_admin" :disabled="isset($user->deleted_at)" class="profileInput" />
                                <label for="is_admin">{{ __('admin.admin_access') }}</label>
                            </div>
                        @endif
                    </div>                

                    <div class="text-right">
                        * {{ __('auth.required_fields') }}
                    </div>
                </div>

                @if ($view == 'admin')
                    @if ($user->barber && $user->barber->deleted_at == null)
                        <div class="border-2 border-dashed rounded-md p-4 border-yellow-400 mb-4">
                            <h3 class="text-xl mb-2 font-base">
                                {{ __('admin.attention') }}
                            </h3>
                            <p>
                                {{ $user->first_name . __('admin.user_is_barber_1') }}
                                <a href="{{ route('barbers.show',$user->barber) }}" class="text-blue-700 hover:underline">{{ __('admin.user_is_barber_2') }}</a>!
                            </p>
                        </div>
                    @endif

                    @if (isset($user->deleted_at))
                        <div class="border-2 border-dashed rounded-md p-4 border-yellow-400 mb-4">
                            <h3 class="text-xl mb-2 font-base">
                                {{ __('admin.attention') }}
                            </h3>
                            <p>{{ $user->first_name . __('admin.user_destroyed') }}</p>
                        </div>
                    @endif

                    @if (!$user->isRegistered())
                        <div class="border-2 border-dashed rounded-md p-4 border-yellow-400 mb-4">
                            <h3 class="text-xl mb-2 font-base">
                                {{ __('admin.attention') }}
                            </h3>
                            <p>{{ $user->first_name . __('admin.user_not_registered') . ' ' . __('admin.encourage_to_reg') }}</p>
                        </div>
                    @endif
                @endif

                <div>
                    <x-button role="ctaMain" :full="true" :disabled="true" id="profileButton">
                        {{ __('users.save_changes') }}
                    </x-button>
                </div>
            </form>
        </x-show-card>
    @else
        <x-empty-card class="mb-4">
            <p class="text-lg max-md:text-base mb-4">
                {{ __('admin.walkin_p1_a') }}
                <span class="font-bold">{{ __('admin.walkin_p1_b') }}</span>.
            </p>

            <p class="max-md:mb-4">
                {{ __('admin.walkin_p2_a') }}

                <a href="{{ isset($appointment) ? route('bookings.show',$appointment) : '' }}" class="text-blue-700 hover:underline">{{ __('admin.walkin_p2_b') }}</a>
                
                {{ __('admin.walkin_p2_c') . $user->first_name . __('admin.walkin_p2_d') }}
            </p>
            <p>{{ __('admin.encourage_to_reg') }}</p>
        </x-empty-card>
    @endif

    @if ($view == 'admin' && $user->hasEmail())
        <x-show-card type="bookings" :show="$showBookings" class="mb-4">
            <x-sum-of-bookings :sumOfBookings="$sumOfBookings" :user="$user" context="bookings" />

            <div class="flex gap-2 mt-8">
                <x-link-button :link="route('bookings.index',['user' => $user->id])" role="ctaMain">
                    {{ __('admin.all_bookings') }}
                </x-link-button>

                @if (!$user->deleted_at)
                    <x-link-button :link="route('bookings.create.barber.service',['user_id' => $user->id])" role="create">
                        {{ __('admin.new_booking') }}
                    </x-link-button>
                @endif
            </div>
        </x-show-card>
    @endif

    @if ($user->barber && !isset($user->barber->deleted_at) && $view != 'admin')
        <x-show-card :show="$showPicture" type="picture" class="mb-4">
            <form action="{{ route('upload-cropped',$user) }}" name="pictureForm" method="post" enctype="multipart/form-data">
                @csrf
                <input type="file" id="selectedImg" class="form-control" accept="image/*" hidden>
                <input type="file" id="croppedImg" name="croppedImg" class="form-control" hidden>
                <input type="hidden" name="lang" id="langCheck" value="{{ App::getLocale() }}">

                <div class="flex gap-8 max-sm:flex-col">
                    <div class="min-w-60">
                        <h3 class="font-bold text-lg mb-2" id="currentPfpTitle">
                            {{ __('users.current_pfp') }}
                        </h3>
                        <div class="relative w-fit max-sm:w-full group cursor-pointer  rounded-md border border-slate-500">
                            <img src="{{ $user->barber->getPicture() }}" alt="Profile picture" id="currentPfp" class="w-60  group-hover:blur-sm transition-all rounded-md max-sm:w-full">
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
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-bold">
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
    @endif

    @if ($view == 'admin' && $user != auth()->user())
        @if ($user->isRegistered())
            @if ($user->deleted_at)
                <x-show-card :show="$showRestore" type="restore" class="mb-4">
                    <p class="mb-2 text-justify">
                        {{ __('admin.restore_user_p1a') . $user->first_name . __('admin.restore_user_p1b') }}
                    </p>

                    <p class="mb-4 text-justify">
                        {{ __('admin.restore_user_p2') }}
                    </p>

                    <form action="{{ route('customers.restore',$user) }}" method="post">
                        @method('PUT')
                        @csrf
                        <x-button role="restoreMain">{{ __('admin.restore_account') }}</x-button>
                    </form>
                </x-show-card>
            @else
                <x-show-card :show="$showDestroy" type="destroy" class="mb-4">
                    <p class="mb-2 text-justify">
                        {{ __('admin.delete_user_p1a') . $user->first_name . __('admin.delete_user_p1b') }}
                    </p>

                    <p class="mb-2 text-justify">
                        {{ __('admin.delete_user_p2') }}
                    </p>

                    <p class="mb-4 text-justify">
                        {{ __('admin.delete_user_p3') }}
                    </p>

                    <form action="{{ route('customers.destroy',$user) }}" method="post">
                        @method('DELETE')
                        @csrf
                        <x-button role="destroyMain">{{ __('admin.delete_account') }}</x-button>
                    </form>
                </x-show-card>
            @endif
        @endif

    @else
        <x-show-card :show="$showPassword" type="password" class="mb-4">
            <form action="{{ route('users.update-password',auth()->user()->id) }}" method="POST" >
                @csrf
                @method('PUT')
                <div class="flex max-md:flex-col gap-4 mb-4">
                    <div class="flex-grow">
                        <div class="flex flex-col mb-2">
                            <x-label for="password">
                                {{ __('users.current_pw') }}*
                            </x-label>
                            <x-input-field type="password" name="password" id="password" class="w-full"/>
                            @error('password')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="flex flex-col mb-2">
                            <x-label for="new_password">
                                {{ __('auth.new_pw') }}*
                            </x-label>
                            <x-input-field type="password" name="new_password" id="new_password" autoComplete="off" class="w-full"/>
                            @error('new_password')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="flex flex-col">
                            <x-label for="new_password_confirmation">
                                {{ __('auth.confirm_new_pw') }}*
                            </x-label>
                            <x-input-field type="password" name="new_password_confirmation" id="new_password_confirmation" autoComplete="off" class="w-full"/>
                            @error('new_password_confirmation')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <x-password-checklist class="flex-grow-0" passwordInput="new_password" passwordConfInput="new_password_confirmation" />
                </div>
                <x-button role="ctaMain" :full="true" id="passButton" :disabled="true">
                    {{ __('users.change_pw') }}
                </x-button>
            </form>
        </x-show-card>

        <x-show-card type="mailing" class="mb-4" :show="$showMailing">
            <div class="mb-4 text-justify">
                <p>
                    {{ __('users.mailing_pref_p') }}
                </p>
            </div>
            <div>
                <form action="{{ route('users.update-mailing',['user' => $user]) }}" method="post">
                    @method('PUT')
                    @csrf
                    
                    <div class="flex gap-2 mb-4">
                        <x-input-field type="checkbox" name="mailing_list_checkbox" id="mailing_list_checkbox" :checked="auth()->user()->subbed_to_mailing_list" />
                        <label for="mailing_list_checkbox">
                            {{ __('users.mailing_pref_label') }}
                        </label>
                    </div>

                    <x-button role="ctaMain" id="mailingListButton" :disabled="true">
                        {{ __('users.save_changes') }}
                    </x-button>
                </form>
            </div>
        </x-show-card>
    @endif

    

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // COUNTS CHARLENGTH OF THE DESCRIPTION TEXTAREA
            const charCount = document.getElementById('charCount');
            const description = document.getElementById('description');

            if (description && charCount) {
                countCharacters(charCount, description);
            }

            // PASSWORD CHANGE
            const passInput = document.getElementById('password');
            const newPassInput = document.getElementById('new_password');
            const newPassConfInput = document.getElementById('new_password_confirmation');
            const passButton = document.getElementById('passButton');
            const passInputs = new Array(passInput, newPassInput, newPassConfInput);

            if (passButton) {
                checkPassword(passButton, passInput, newPassInput, newPassConfInput);

                passInputs.forEach(input => {
                    input.addEventListener('input', function () {
                        checkPassword(passButton, passInput, newPassInput, newPassConfInput);
                    });
                });
            }            

            // PROFILE BUTTON RE-ENABLE
            const profileButton = document.getElementById('profileButton');
            const profileInputs = document.querySelectorAll('.profileInput');
            const profileReqInputs = document.querySelectorAll('.profileReqInput');

            enableButtonIfInputsFilled(profileButton, profileInputs, profileReqInputs);

            // MAILING LIST PREF BUTTON RE-ENABLE
            const mailingListButton = document.getElementById('mailingListButton');
            const mailingListCheckbox = document.getElementById('mailing_list_checkbox');

            mailingListCheckbox.addEventListener('change',()=>{
                mailingListButton.disabled = false;
            });
        });

        function checkPassword(passButton, passInput, newPassInput, newPassConfInput) {
            const passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
            passButton.disabled = true;
            
            if (passInput.value != '' && newPassInput.value == newPassConfInput.value && passRegex.test(newPassInput.value)) {
                passButton.disabled = false;
            }
        }
    </script>
</x-user-layout>