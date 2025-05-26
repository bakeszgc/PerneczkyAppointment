<x-user-layout title="Account Settings - ">
    <x-breadcrumbs :links="[
        'Account Settings' => ''
    ]" />
    <h1 class="font-extrabold text-4xl mb-4">Account Settings</h1>

    <x-show-card :show="$showProfile" type="profile" class="mb-4">
        <form action="{{ route('users.update',$user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <div class=" grid grid-cols-2 gap-2">
                    <div class="flex flex-col">
                        <x-label for="first_name">First name</x-label>
                        <x-input-field name="first_name" id="first_name" value="{{ old('first_name') ??$user->first_name }}" />
                        @error('first_name')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="flex flex-col">
                        <x-label for="last_name">Last name</x-label>
                        <x-input-field name="last_name" id="last_name" value="{{ old('last_name') ??$user->last_name }}" />
                        @error('last_name')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    @if ($user->barber)
                        <div class="flex flex-col col-span-2">
                            <x-label for="display_name">Display name</x-label>
                            <x-input-field name="display_name" id="display_name" value="{{ old('display_name') ??$user->barber->display_name }}" />
                            @error('display_name')
                                <p class=" text-red-500">{{$message}}</p>
                            @enderror
                        </div>
                    @endif

                    <div class="flex flex-col col-span-2">
                        <div class="flex justify-between items-end">
                            <x-label for="email">Email address</x-label>
                            @if ($user->email_verified_at === null)
                                <a href="{{ route('verification.notice') }}"class="font-bold text-base text-blue-500 hover:underline">Verify your email here</a>
                                <!-- <button form="verification" class="font-bold text-base text-blue-500 hover:underline">Verify your email here</button> -->
                            @else
                                <p class="text-slate-500 text-sm">Verified on {{ date_format($user->email_verified_at,'d M Y')  }}</p>
                            @endif
                            
                        </div>
                        
                        <x-input-field type="email" name="email" id="email" value="{{ old('email') ?? $user->email }}" />
                        @error('email')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="flex flex-col">
                        <x-label for="date_of_birth">Date of birth</x-label>
                        <x-input-field type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') ?? $user->date_of_birth }}" />
                        @error('date_of_birth')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="flex flex-col">
                        <x-label for="telephone_number">Telephone number</x-label>
                        <x-input-field type="tel" name="telephone_number" id="telephone_number" value="{{ old('telephone_number') ?? $user->tel_number }}" />
                        @error('telephone_number')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                </div>
            </div>
            <div>
                <x-button role="loginMain" :full="true">Save Changes</x-button>
            </div>
        </form>
    </x-show-card>

    <x-show-card :show="$showPicture" type="picture" class="mb-4">
        <div class="grid grid-cols-2">

        
            <div class="flex">
                <div>
                    <h3 class="font-bold text-lg">Your current profile picture</h3>
                    <div class="relative w-fit group cursor-pointer">
                        <img src="{{ asset('pfp/blank.png') }}" alt="" class="w-40 border border-slate-500 group-hover:blur-sm transition-all">
                        <div class="absolute w-full h-full top-0 flex items-center justify-center group-hover:bg-black group-hover:bg-opacity-75 transition-all">
                            <label for="selectedImg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="size-6 opacity-0 group-hover:opacity-100 transition-all cursor-pointer">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                </svg>
                            </label>
                        </div>
                    </div>
                </div>
                
            </div>
            <div>
                <p class="mb-1">This picture of you will appear on the homepage and during the appointment booking process. Please take into account the followings before modifying your image:</p>
                <ul class="list-disc *:ml-6 mb-4">
                        <li>
                            The picture needs to be cropped to a 1:1 (square) aspect ratio
                        </li>
                        <li>
                            The uploaded file cannot be bigger than 2 MB.
                        </li>
                    </ul>
            </div>
        </div>
        <div>
            <div class="preview w-40 h-40 overflow-hidden">
            </div>
            
            <div>
                <form action="/upload-cropped" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="selectedImg" class="form-control" accept="image/*" hidden>
                    <input type="file" id="croppedImg" name="croppedImg" class="form-control" hidden>
                    <button type="submit" id="submit" class="my-2 btn btn-primary" disabled>Save</button>
                </form>
                <img src="" alt="" id="image">
                <button id="crop" class="my-2 btn btn-primary" hidden>Crop</button>
            </div>
        </div>
        <button id="openModal">open</button>
    </x-show-card>

    <div class="modal" id="modal">
        <p>check check</p>
        <button id="closeModal">close</button>
    </div>
    
    <script type="text/javascript" src="{{ asset('storage/js/cropper.js') }}"></script>

    <x-show-card :show="$showPassword" type="password" class="mb-4">
        <form action="{{ route('users.update-password',auth()->user()->id) }}" method="POST" >
            @csrf
            @method('PUT')
            <div class="flex gap-4 mb-4">
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
            <x-button role="loginMain" :full="true">Change Password</x-button>
        </form>
    </x-show-card>

    <x-show-card :show="$showDestroy" type="destroy" class="mb-4">
        <p class="mb-4">By deleting your account you will not be able to book upcoming appointments or access your previous bookings. Are you sure you want to proceed?</p>
        <form action="{{ route('users.destroy',$user) }}">
            <x-button role="destroyMain">Delete my account</x-button>
        </form>
    </x-show-card>
</x-user-layout>