<x-user-layout title="Account Settings - ">
    <x-breadcrumbs :links="[
        'Account Settings' => ''
    ]" />
    <h1 class="font-extrabold text-4xl mb-4">Account Settings</h1>

    <x-card class="mb-6">
        <form action="{{ route('users.update',$user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="flex gap-4 mb-4 max-md:flex-col">
                @if ($user->barber)
                    <div>
                        <h2 class="text-xl font-bold mb-2">Profile picture</h2>
                        <img src="{{ asset('pfp/blank.png') }}" alt="profile picture" class=" h-64 max-md:w-full max-md:h-auto border-2 border-blue-500 rounded-md mb-2">
                        <input type="file">
                    </div>
                @endif
                <div class="flex-1">
                    <h2 class="text-xl font-bold mb-2">Personal data</h2>
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
            </div>
            <div>
                <x-button role="loginMain" :full="true">Save Changes</x-button>
            </div>
        </form>
    </x-card>

    <x-card x-data="{open: {{ $showPassword ? 'true' : 'false' }}}" class="mb-4">
        <div class="flex justify-between items-center cursor-pointer transition-all" @click="open = !open">
            <h2 class="text-xl font-bold flex gap-2 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                </svg>
                <span>Change your password</span>
            </h2>
            <div class="hover:bg-blue-100 transition-all rounded-full p-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" x-bind:class="!open ? 'size-6 transition-all' : 'rotate-180 size-6 transition-all'">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 5.25 7.5 7.5 7.5-7.5m-15 6 7.5 7.5 7.5-7.5"/>
                </svg>
            </div>
        </div>

        <form action="{{ route('users.update-password',auth()->user()->id) }}" method="POST" x-show="open" x-transition>
            @csrf
            @method('PUT')
            <div class="flex gap-4 mt-4 mb-4">
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
                
                <div class="flex-grow-0 pr-8">
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

    </x-card>
</x-user-layout>