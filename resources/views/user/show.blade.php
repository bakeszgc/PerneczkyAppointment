<x-user-layout title="Account Settings - ">
    <x-breadcrumbs :links="[
        'Account Settings' => ''
    ]" />
    <h1 class="font-extrabold text-4xl mb-4">Account Settings</h1>
    <x-card class="mb-4">
        <form id="verification" action="{{ route('verification.send') }}" method="POST"></form>
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

                        
                        
                        <div class="flex flex-col col-span-1">
                            <x-label for="password">Password</x-label>
                            <p>Want to change your password? <a href="">Click here!</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-2">
                <x-button role="loginMain" :full="true">Save Changes</x-button>
            </div>
            <div class="text-center">
                <a href="" class=" text-red-500 font-bold hover:underline">Delete your account</a>
            </div>
        </form>
    </x-card>
</x-user-layout>