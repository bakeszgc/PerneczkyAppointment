@php
    $view = $view ?? 'user';

    if($view == 'admin') {
        $title = $user->first_name . ' ' . $user->last_name;
        $updateRoute = route('customers.update',$user);
        $breadcrumbLinks = [
            'Admin Dashboard' => route('admin'),
            'Manage Customers' => route('customers.index'),
            $title => ''
        ];
    } else {
        $title = 'Account Settings';
        $updateRoute = route('users.update',$user);
        $breadcrumbLinks = [
            'Account Settings' => ''
        ];
    }
@endphp

<x-user-layout title="{{ $title }}">
    <x-breadcrumbs :links="$breadcrumbLinks" />

    <x-headline class="mb-4">
        <span @class(['text-slate-500' => isset($user->deleted_at)])>
            {{ $title }} {{ isset($user->deleted_at) ? '(deleted)' : '' }}
        </span>
    </x-headline>

    <x-show-card :show="$showProfile" type="profile" class="mb-4">
        <form action="{{ $updateRoute }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <div class="grid grid-cols-2 gap-2">
                    <div class="flex flex-col max-sm:col-span-2">
                        <x-label for="first_name">First name</x-label>
                        <x-input-field name="first_name" id="first_name" value="{{ old('first_name') ??$user->first_name }}" :disabled="isset($user->deleted_at)" class="profileInput" />
                        @error('first_name')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col max-sm:col-span-2">
                        <x-label for="last_name">Last name</x-label>
                        <x-input-field name="last_name" id="last_name" value="{{ old('last_name') ??$user->last_name }}" :disabled="isset($user->deleted_at)" class="profileInput" />
                        @error('last_name')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    @if ($user->barber && !isset($user->barber->deleted_at) && $view != 'admin')
                        <div class="flex flex-col col-span-2">
                            <x-label for="display_name">Display name</x-label>
                            <x-input-field name="display_name" id="display_name" value="{{ old('display_name') ??$user->barber->display_name }}" :disabled="isset($user->deleted_at)" class="profileInput" />
                            @error('display_name')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col col-span-2">
                            <x-label for="description">Description (<span id="charCount">xxx</span>/500)</x-label>
                            <x-input-field type="textarea" name="description" id="description" :disabled="isset($user->deleted_at) || isset($user->barber->deleted_at)" class="profileInput">{{ old('comment') ?? $user->barber->description }}</x-input-field>
                            @error('description')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror
                        </div>
                    @endif

                    <div class="flex flex-col col-span-2">
                        <div class="flex justify-between items-end">
                            <x-label for="email">Email address</x-label>
                            <div class="text-right">
                                @if ($user->email_verified_at === null)
                                    @if ($view == 'admin')
                                        <p class="text-slate-500 text-sm">Not verified yet</p>
                                    @else
                                        <a href="{{ route('verification.notice') }}"class="font-bold text-base max-md:text-sm text-blue-500 hover:underline">Verify your email here</a>
                                    @endif
                                @else
                                    <p class="text-slate-500 text-sm">Verified on {{ date_format($user->email_verified_at,'d M Y')  }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <x-input-field type="email" name="email" id="email" value="{{ old('email') ?? $user->email }}" :disabled="isset($user->deleted_at)" class="profileInput" />
                        @error('email')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col max-sm:col-span-2">
                        <x-label for="date_of_birth">Date of birth</x-label>
                        <x-input-field type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') ?? $user->date_of_birth }}" :disabled="isset($user->deleted_at)" class="profileInput" />
                        @error('date_of_birth')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col max-sm:col-span-2">
                        <x-label for="telephone_number">Telephone number</x-label>
                        <x-input-field type="tel" name="telephone_number" id="telephone_number" value="{{ old('telephone_number') ?? $user->tel_number }}" :disabled="isset($user->deleted_at)" class="profileInput" />
                        @error('telephone_number')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                </div>
            </div>

            @if ($view == 'admin')
                <div class="mb-4 *:flex *:items-center *:gap-2">
                    <div class="mb-2">
                        <x-input-field type="checkbox" name="is_barber" id="is_barber" value="1" :checked="$user->barber && $user->barber->deleted_at == null" :disabled="isset($user->deleted_at)" />
                        <label for="is_barber">Barber access</label>
                    </div>

                    <div>
                        <x-input-field type="checkbox" name="is_admin" id="is_admin" value="1" :checked="$user->is_admin" :disabled="isset($user->deleted_at)" />
                        <label for="is_admin">Admin access</label>
                    </div>
                </div>

                @if ($user->barber && $user->barber->deleted_at == null)
                    <div class="border-2 border-dashed rounded-md p-4 border-yellow-400 mb-4">
                        <h3 class="text-xl mb-2 font-base">Attention</h3>
                        <p>{{ $user->first_name }} is one of your employees and you are currently viewing his customer page. If you want to edit their details or see their stats as barbers then check his <a href="{{ route('barbers.show',$user->barber) }}" class="text-blue-700 hover:underline">barber page!</a></p>
                    </div>
                @endif

                @if (isset($user->deleted_at))
                    <div class="border-2 border-dashed rounded-md p-4 border-yellow-400 mb-4">
                        <h3 class="text-xl mb-2 font-base">Attention</h3>
                        <p>{{ $user->first_name }}'s account is currently disabled. If you want to edit their details you need to restore the account first!</p>
                    </div>
                @endif
            @endif

            <div>
                <x-button role="ctaMain" :full="true" :disabled="true" id="profileButton">Save changes</x-button>
            </div>
        </form>
    </x-show-card>

    @if ($view == 'admin')
        <x-show-card type="bookings" :show="$showBookings" class="mb-4">
            <x-sum-of-bookings :sumOfBookings="$sumOfBookings" :user="$user" context="bookings" />

            <div class="flex gap-2 mt-8">
                <x-link-button :link="route('bookings.index',['user' => $user->id])" role="ctaMain">All bookings</x-link-button>

                @if (!$user->deleted_at)
                    <x-link-button :link="route('bookings.create.barber.service',['user_id' => $user->id])" role="create">New booking</x-link-button>
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

                <div class="flex gap-8 max-sm:flex-col">
                    <div class="min-w-60">
                        <h3 class="font-bold text-lg mb-2" id="currentPfpTitle">Current profile picture</h3>
                        <div class="relative w-fit group cursor-pointer  rounded-md border border-slate-500">
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
                        <h3 class="font-bold text-lg mb-2">Guidelines</h3>
                        <p class="mb-2">This picture will appear on the homepage and during the appointment booking process. Please take into account the followings before modifying your image:</p>
                        <ul class="list-disc *:ml-6 *:mb-1 mb-4">
                            <li>
                                The picture of you needs to be a clear and high quality image
                            </li>
                            <li>
                                Use neutral backgrounds for your profile picture
                            </li>
                            <li>
                                Avoid using group pictures
                            </li>
                            <li>
                                Do not upload any inappropriate, offensive or irrelevant images
                            </li>
                            <li>
                                Be aware that you will have to crop it to a 1:1 (square) aspect ratio
                            </li>
                            <li>
                                The uploaded file cannot exceed 4 MB
                            </li>
                        </ul>
                        <div id="submitDiv" hidden>
                            <x-button id="submit" role="ctaMain">Save changes</x-button>
                        </div>
                    </div>
                </div>
            </form>
        </x-show-card>

        <x-modal id="cropModal">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-bold">Crop your photo</h1>
                <div id="closeModal" class="hover:bg-blue-100 transition-all rounded-full p-2 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
            <img src="" alt="" id="image" class="max-w-xl max-h-[50vh]">
            <div class="flex gap-2 mt-4">
                <x-button id="crop" :hidden="true" role="ctaMain">Crop</x-button>
                <x-button id="reset" :hidden="true">Reset</x-button>
            </div>
        </x-modal>
    @endif

    @if ($view == 'admin')

        @if ($user->deleted_at)
            <x-show-card :show="$showRestore" type="restore" class="mb-4">
                <p class="mb-2 text-justify">
                    This account has been deleted and can no longer be accessed. {{ $user->first_name }} cannot log in, view their appointments, or create new ones.
                </p>

                <p class="mb-4 text-justify">
                    If you wish to have your account restored. You must restore the account to re-enable their access and be able to edit their details on the admin page.
                </p>

                <form action="{{ route('customers.restore',$user) }}" method="post">
                    @method('PUT')
                    @csrf
                    <x-button role="ctaMain">Restore account</x-button>
                </form>
            </x-show-card>
        @else
            <x-show-card :show="$showDestroy" type="destroy" class="mb-4">
                <p class="mb-4">Deleting this account will prevent {{ $user->first_name }} from logging in, viewing their appointments, or creating new ones. Their upcoming appointments will be cancelled and their admin or barber roles will be revoked. The account can only be restored by an administrator. Are you sure you want to proceed?</p>

                <form action="{{ route('customers.destroy',$user) }}" method="post">
                    @method('DELETE')
                    @csrf
                    <x-button role="destroyMain">Delete account</x-button>
                </form>
            </x-show-card>
        @endif

    @else
        <x-show-card :show="$showPassword" type="password" class="mb-4">
            <form action="{{ route('users.update-password',auth()->user()->id) }}" method="POST" >
                @csrf
                @method('PUT')
                <div class="flex max-md:flex-col gap-4 mb-4">
                    <div class="flex-grow">
                        <div class="flex flex-col mb-2">
                            <x-label for="password">Your current password*</x-label>
                            <x-input-field type="password" name="password" id="password"/>
                            @error('password')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="flex flex-col mb-2">
                            <x-label for="new_password">Your new password*</x-label>
                            <x-input-field type="password" name="new_password" id="new_password"/>
                            @error('new_password')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror
                        </div>
                        <div class="flex flex-col">
                            <x-label for="new_password_confirmation">Your new password again*</x-label>
                            <x-input-field type="password" name="new_password_confirmation" id="new_password_confirmation"/>
                            @error('new_password_confirmation')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex-grow-0">
                        <span class="font-semibold text-base">Your password must contain</span>
                        <ul class="list-disc *:ml-6 mb-4">
                            <li>
                                at least one <span class="font-semibold">undercase letter</span>
                            </li>
                            <li>
                                at least one <span class="font-semibold">uppercase letter</span>
                            </li>
                            <li>
                                at least one <span class="font-semibold">number</span>
                            </li>
                            <li>
                                and be at least <span class="font-semibold">8 characters long</span>
                            </li>
                        </ul>
                        Fields marked with * are <span class="font-semibold">required</span>
                    </div>
                </div>
                <x-button role="ctaMain" :full="true" id="passButton" :disabled="true">Change password</x-button>
            </form>
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

            enableButton(profileButton, profileInputs);
        });

        function checkPassword(passButton, passInput, newPassInput, newPassConfInput) {
            const passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
            passButton.disabled = true;
            
            if (passInput.value != '' && newPassInput.value == newPassConfInput.value && passRegex.tes(newPassInput.value)) {
                passButton.disabled = false;
            }
        }
    </script>
</x-user-layout>