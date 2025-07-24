<x-user-layout currentView="admin" :title="$barber->getName() . ' - '">
    <x-breadcrumbs :links="[
        'Admin Dashboard' => route('admin'),
        'Manage Barbers' => route('barbers.index'),
        $barber->getName() => ''
    ]"/>

    <x-headline class="mb-4">{{ $barber->getName() }} {{ $barber->isDeleted() }}</x-headline>

    <x-show-card :show="$showProfile" type="profile" class="mb-4">
        <form action="{{ route('barbers.update',$barber) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <div class="grid grid-cols-2 gap-2 mb-4">
                    <div class="flex flex-col">
                        <x-label for="first_name">First name</x-label>
                        <x-input-field name="first_name" id="first_name" value="{{ old('first_name') ??$barber->user->first_name }}" />
                        @error('first_name')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="flex flex-col">
                        <x-label for="last_name">Last name</x-label>
                        <x-input-field name="last_name" id="last_name" value="{{ old('last_name') ??$barber->user->last_name }}" />
                        @error('last_name')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col col-span-2">
                        <x-label for="display_name">Display name</x-label>
                        <x-input-field name="display_name" id="display_name" value="{{ old('display_name') ??$barber->display_name }}" />
                        @error('display_name')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col col-span-2">
                        <div class="flex justify-between items-end">
                            <x-label for="email">Email address</x-label>
                            @if ($barber->user->email_verified_at === null)
                                <a href="{{ route('verification.notice') }}"class="font-bold text-base text-blue-500 hover:underline">Verify your email here</a>
                                <!-- <button form="verification" class="font-bold text-base text-blue-500 hover:underline">Verify your email here</button> -->
                            @else
                                <p class="text-slate-500 text-sm">Verified on {{ date_format($barber->user->email_verified_at,'d M Y')  }}</p>
                            @endif
                            
                        </div>
                        
                        <x-input-field type="email" name="email" id="email" value="{{ old('email') ?? $barber->user->email }}" />
                        @error('email')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="flex flex-col">
                        <x-label for="date_of_birth">Date of birth</x-label>
                        <x-input-field type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') ?? $barber->user->date_of_birth }}" />
                        @error('date_of_birth')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="flex flex-col">
                        <x-label for="telephone_number">Telephone number</x-label>
                        <x-input-field type="tel" name="telephone_number" id="telephone_number" value="{{ old('telephone_number') ?? $barber->user->tel_number }}" />
                        @error('telephone_number')
                            <p class=" text-red-500">{{$message}}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4 flex gap-2">
                    <x-input-field type="checkbox" name="is_visible" id="is_visible" :checked="$barber->is_visible" value="is_visible" :disabled="isset($barber->deleted_at)"></x-input-field>
                    <label for="is_visible">
                        Visible for everyone
                    </label>
                    @error('is_visible')
                        <p class=" text-red-500">{{$message}}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex gap-2">
                <x-button role="ctaMain" :disabled="isset($barber->deleted_at)">Save Changes</x-button>
                </form>

                @if ($barber->deleted_at)
                    <form action="{{ route('barbers.restore',$barber) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <x-button role="restore">Restore Barber Access</x-button>
                    </form>
                @else
                    <form action="{{ route('barbers.destroy',$barber) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <x-button role="destroy">Remove Barber Access</x-button>
                    </form>
                @endif
            </div>
        
    </x-show-card>

    <x-show-card :show="$showPicture" type="picture" class="mb-4">
        <form action="{{ route('upload-cropped',$barber->user) }}" name="pictureForm" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="source" value="admin">
            <input type="file" id="selectedImg" class="form-control" accept="image/*" hidden>
            <input type="file" id="croppedImg" name="croppedImg" class="form-control" hidden>

            <div class="flex gap-8 max-sm:flex-col">
                <div class="min-w-60">
                    <h3 class="font-bold text-lg mb-2" id="currentPfpTitle">Current profile picture</h3>
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
                    <h3 class="font-bold text-lg mb-2">Guidelines</h3>
                    <p class="mb-2">This picture will appear on the homepage and during the appointment booking process. Please take into account the followings before modifying your image:</p>
                    <ul class="list-disc *:ml-6 *:mb-1 mb-4">
                        <li>
                            The picture of the barber needs to be a clear and high quality image
                        </li>
                        <li>
                            Use neutral backgrounds for this barber's profile picture
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
                        <x-button id="submit" role="ctaMain">Save Changes</x-button>
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
    
    <x-show-card :show="$showBookings" type="bookings" class="mb-4">
        <div class="grid grid-cols-3 mb-8">
            
            @foreach ($sumOfBookings as $bookingType => $sumOfBooking)
                <div class="even:border-x-2">
                    @php
                        $arguments = ['barber' => $barber->id];

                        if ($bookingType != 'cancelled') {
                            $arguments['time_window'] = $bookingType;
                        } else {
                            $arguments['cancelled'] = 2;
                        }
                    @endphp

                    <a href="{{ route('bookings.index',$arguments) }}" class="flex flex-col items-center hover:text-[#0018d5] transition-all">
                        @switch($bookingType)
                            @case('upcoming')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-20">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061A1.125 1.125 0 0 1 3 16.811V8.69ZM12.75 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061a1.125 1.125 0 0 1-1.683-.977V8.69Z" />
                                </svg>
                                @break
                            @case('previous')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-20">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 16.811c0 .864-.933 1.406-1.683.977l-7.108-4.061a1.125 1.125 0 0 1 0-1.954l7.108-4.061A1.125 1.125 0 0 1 21 8.689v8.122ZM11.25 16.811c0 .864-.933 1.406-1.683.977l-7.108-4.061a1.125 1.125 0 0 1 0-1.954l7.108-4.061a1.125 1.125 0 0 1 1.683.977v8.122Z" />
                                </svg>
                            @break
                            @case('cancelled')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-20">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            @break
                        @endswitch

                        <h2 class="text-xl font-bold mb-2">{{ ucfirst($bookingType) }}</h2>
                    </a>

                    <div class="flex justify-between w-full px-2">
                        <div class="flex-1">
                            <p>Number of bookings</p>
                            <p>Income</p>
                        </div>
                        <div class="text-right">
                            <p>{{ $sumOfBooking['count'] }}</p>
                            <p>{{ number_format($sumOfBooking['income'],thousands_separator:' ') }} HUF</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div>
            <x-link-button :link="route('bookings.index',['barber' => $barber->id])" :full="true">All bookings</x-link-button>
        </div>
    </x-show-card>
</x-user-layout>