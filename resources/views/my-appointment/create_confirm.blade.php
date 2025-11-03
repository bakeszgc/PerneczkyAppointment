@php
    $comment ??= '';
    $view ??= 'user';
    $action ??= 'create';
    $steps = [true,true,true];

    $firstName = $view == 'user' ? 'your' : ($user?->first_name ?? old('first_name') ?? request('first_name') ?? '');
    $email = request('email') ?? old('email') ?? $user->email ?? '';

    switch($view) {
        case 'user':
            switch($action) {
                case 'create':
                    $serviceRoute = route('my-appointments.create.barber.service',['service_id' => $service->id, 'barber_id' => $barber->id]);
                    $dateRoute = route('my-appointments.create.date',['service_id' => $service->id, 'barber_id' => $barber->id, 'comment' => $comment, 'date' => $startTime->format('Y-m-d G:i')]);
                    $storeRoute = route('my-appointments.store');

                    $breadcrumbLinks = [
                        'Barber & service' => $serviceRoute,
                        'Date & time' => $dateRoute,
                        'Confirm' => ''
                    ];
                break;

                case 'edit':
                    $serviceRoute = route('my-appointments.edit.barber.service',['service_id' => $service->id, 'barber_id' => $barber->id, 'my_appointment' => $appointment]);
                    $dateRoute = route('my-appointments.edit.date',['service_id' => $service->id, 'barber_id' => $barber->id, 'comment' => $comment, 'date' => $startTime->format('Y-m-d G:i'), 'my_appointment' => $appointment]);
                    $storeRoute = route('my-appointments.update', $appointment);

                    $breadcrumbLinks = [
                        'My appointments' => route('my-appointments.index'),
                        'Appointment #' . $appointment->id => route('my-appointments.show',$appointment),
                        'Barber & service' => $serviceRoute,
                        'Date & time' => $dateRoute,
                        'Confirm' => ''
                    ];
                break;
            }
        break;

        case 'barber':
            $serviceRoute = route('appointments.create.service',['service_id' => $service->id]);
            $dateRoute = route('appointments.create.date',['service_id' => $service->id, 'comment' => $comment, 'date' => $startTime->format('Y-m-d G:i')]);
            $customerLink = route('appointments.create.customer',['service_id' => $service->id, 'comment' => $comment, 'date' => $startTime->format('Y-m-d G:i')]);
            $storeRoute = route('appointments.store');

            $breadcrumbLinks = [
                'Bookings' => route('appointments.index'),
                'Service' => $serviceRoute,
                'Date & time' => $dateRoute,
                'Customer' => $customerLink,
                'Confirm' => ''
            ];

            $steps[] = true;
        break;

        case 'admin':
            $serviceRoute = route('bookings.create.barber.service',['service_id' => $service->id, 'barber_id' => $barber->id]);
            $dateRoute = route('bookings.create.date',['service_id' => $service->id, 'comment' => $comment, 'date' => $startTime->format('Y-m-d G:i'), 'barber_id' => $barber->id]);
            $customerLink = route('bookings.create.customer',['service_id' => $service->id, 'comment' => $comment, 'date' => $startTime->format('Y-m-d G:i'), 'barber_id' => $barber->id]);
            $storeRoute = route('bookings.store');

            $breadcrumbLinks = [
                'Admin dashboard' => route('admin'),
                'Bookings' => route('bookings.index'),
                'Barber & service' => $serviceRoute,
                'Date & time' => $dateRoute,
                'Customer' => $customerLink,
                'Confirm' => ''
            ];

            $steps[] = true;
        break;
    }
@endphp

<x-user-layout title="New {{ $view == 'user' ? 'appointment' : 'booking' }}" currentView="{{ $view }}">

    <x-breadcrumbs :links="$breadcrumbLinks" />

    <div class="flex justify-between">
        <x-headline class="mb-4">Confirm {{ $view == 'user' ? 'your appointment' : 'this booking' }}</x-headline>

        <div class="w-16 flex gap-1">                
            @foreach ($steps as $step)
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="40" stroke="#93c5fd" stroke-width="6" fill="{{ $step ? '#93c5fd' : 'none' }}" />
                </svg>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-3 max-sm:grid-cols-1 gap-4 mb-4">
        <x-card class="h-fit flex max-[540px]:flex-col sm:flex-col gap-4">
            
            <div class="rounded-md overflow-hidden">
                <img src="{{ $barber->getPicture() }}" alt="{{ $barber->getName() }}" class="hover:scale-105 transition-all">
            </div>

            <div class="min-w-fit">
                <h2 class="font-bold text-lg mb-2">{{ $view == 'user' ? 'Your' : 'New' }} appointment</h2>

                <div class="grid grid-cols-1 max-[540px]:grid-cols-2 gap-2">                
                    <div>
                        <h3 class="font-bold">
                            Barber
                        </h3>

                        @if ($view == 'barber')
                            <p>
                        @else
                            <a href="{{ $serviceRoute }}" class="text-blue-700 hover:underline">
                        @endif
                        
                            {{ $barber->getName() }}
                            
                        @if ($view == 'barber')
                            </p>
                        @else
                            </a>
                        @endif
                    </div>

                    <div class="max-[540px]:text-right">
                        <h3 class="font-bold">Service</h3>
                        <p>
                            <a href="{{ $serviceRoute }}" class="text-blue-700 hover:underline">
                                {{ $service->name }}
                            </a>
                        </p>
                    </div>

                    <div>
                        <h3 class="font-bold">Price</h3>
                        <p>{{ $service->price }} HUF</p>
                    </div>

                    <div class="max-[540px]:text-right">
                        <h3 class="font-bold">Date & time</h3>
                        <a href="{{ $dateRoute }}" class="text-blue-700 hover:underline">
                            {{ $startTime->format('Y-m-d G:i') }}
                        </a>
                    </div>

                    <div>
                        <h3 class="font-bold">Duration</h3>
                        <p>{{ $service->duration }} minutes</p>
                    </div>

                    <div class="max-[540px]:text-right">
                        <h3 class="font-bold">Comment</h3>
                        <a href="{{ $dateRoute }}" @class(['text-blue-700 hover:underline', 'italic' => $comment == ''])>
                            {{ $comment != '' ? $comment : "No comments from you." }}
                        </a>
                    </div>
                </div>
            </div>
        </x-card>

        <div class="col-span-2 max-sm:col-span-1">

            <x-card class="mb-4 text-justify">
                @guest
                    <h2 class="text-lg font-bold mb-2">Introduce yourself</h2>
                    <p class="mb-4">We are almost done! But first we need a couple information from you to know who are we going to give a fresh look to. Please fill these input fields below or even better if you log in or register. Pro tip: it will make your life a lot easier for your next appointment 😉</p>
                @endguest

                @if ($view != 'user' && !isset($user))
                    <h2 class="text-lg font-bold mb-2">Introduce your guest</h2>
                    <p class="mb-4">We are almost done! But first we need a couple information from your customer to know who are we going to give a fresh look to. Please fill these input fields below!</p>
                @endif

                <h2 class="text-lg font-bold mb-2">Everything looks fine?</h2>
                <p>Before confirming and finalizing {{ $firstName != '' ? ($firstName . ($firstName != 'your' ? "'s" : '')) : 'this' }} appointment be sure to take a second to double check the details on the left. If anything is wrong feel free to click on it to modify it.</p>
            </x-card>

            <x-card class="mb-4 text-center">
                @guest
                    <h2 class="font-bold text-lg mb-4">Are you a returning customer?</h2>

                    <div class="flex items-center gap-2 justify-center mb-8">
                        <x-link-button role="ctaMain" link="{{ route('login') }}?from=appConfirm">Log in</x-link-button>
                        <p>or</p>
                        <x-link-button role="active" link="{{ route('register') }}?from=appConfirm">Create an account</x-link-button>
                    </div>

                    <div class=" mx-auto mb-6 flex gap-2 justify-center items-center">
                        <hr class="w-1/4">
                        <p class="text-slate-500">or alternatively</p>
                        <hr class="w-1/4">
                    </div>

                    <h2 class="font-bold text-lg mb-4">Book appointment without account</h2>
                @endguest

                <div class="w-full text-left">
                    <form action="{{ $storeRoute }}" method="POST">
                        @if ($action == 'edit')
                            @method('PUT')
                        @endif
                        @csrf

                        @if ($view != 'barber')
                            <input type="hidden" name="barber_id" value="{{ $barber->id }}">
                        @endif
                        
                        @if ($view != 'user' && isset($user))
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                        @endif
                        
                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                        <input type="hidden" name="date" value="{{ $startTime }}">
                        <input type="hidden" name="comment" value="{{ $comment }}">

                        <div class="mb-4">
                            <x-label for="first_name">{{ $view == 'user' ? 'First' : "Customer's first" }} name*</x-label>
                            <x-input-field id="first_name" name="first_name" class="w-full confirmInput reqInput" :disabled="$view == 'user' ? auth()->user() != null : isset($user)" autoComplete="on" :value="$view == 'user' ? (auth()->user()?->first_name ?? '') : $firstName" />
                        </div>

                        <div class="mb-4">
                            <div class="flex justify-between items-center">
                                <x-label for="email">{{ $view == 'user' ? 'Email*' : "Customer's email" }}</x-label>
                                @if ($view != 'user')
                                    <p>Leave empty for walk-ins</p>
                                @endif
                            </div>
                            <x-input-field type="email" id="email" name="email" @class(['w-full confirmInput', 'reqInput' => $view == 'user']) :disabled="$view == 'user' ? auth()->user() != null : isset($user)" autoComplete="on" :value="$view == 'user' ? (auth()->user()?->email ?? '') : $email"  />
                        </div>

                        <div class="flex gap-2 items-center mb-2">
                            <x-input-field type="checkbox" name="confirmation_checkbox" id="confirmation_checkbox" value="1" class="confirmInput reqInput"/>
                            <label for="confirmation_checkbox" class="flex-1">Yes, the appointment details match to my expectations.*</label>

                            @error('confirmation_checkbox')
                                <p class=" text-red-500 text-right">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="flex gap-2 items-center mb-4">
                            <x-input-field type="checkbox" name="policy_checkbox" id="policy_checkbox" value="1" class="confirmInput reqInput"/>
                            <label for="policy_checkbox" class="flex-1">I have read and I accept the Terms & conditions and the Data privacy.*</label>

                            @error('policy_checkbox')
                                <p class=" text-red-500 text-right">{{$message}}</p>
                            @enderror
                        </div>

                        <div>
                            <x-button role="ctaMain" :full="true" id="confirmButton" :disabled="true">Confirm {{ $view == 'user' ? 'appointment' : 'booking' }}</x-button>
                        </div>
                    </form>
                </div>
            </x-card>            
        </div>

        
    </div>

    <script>
        
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('.confirmInput');
            const reqInputs = document.querySelectorAll('.reqInput');
            const button = document.getElementById('confirmButton');

            button.disabled = true;

            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    enableButtonIfInputsFilled(button,inputs,reqInputs);
                });
            });
        });
    </script>
</x-user-layout>