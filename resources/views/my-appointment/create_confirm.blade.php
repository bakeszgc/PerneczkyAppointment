@php
    $comment ??= '';
@endphp

<x-user-layout>

    <x-breadcrumbs :links="[
        'Barber & service' => '',
        'Date & time' => '',
        'Confirm' => ''
    ]" />

    <x-headline class="mb-4">Confirm your appointment</x-headline>

    <div class="grid grid-cols-3 max-sm:grid-cols-1 gap-4">
        <x-card class="h-fit flex max-[540px]:flex-col sm:flex-col gap-4">
            
            <div class="rounded-md overflow-hidden">
                <img src="{{ $barber->getPicture() }}" alt="{{ $barber->getName() }}" class="hover:scale-105 transition-all">
            </div>

            <div class="min-w-fit">
                <h2 class="font-bold text-lg mb-2">Your appointment</h2>

                <div class="grid grid-cols-1 max-[540px]:grid-cols-2 gap-2">                
                    <div>
                        <h3 class="font-bold">
                            Barber
                        </h3>
                        <p>
                            <a href="{{ route('my-appointments.create.barber.service',['service_id' => $service->id, 'barber_id' => $barber->id]) }}" class="text-blue-700 hover:underline">{{ $barber->getName() }}</a>
                        </p>
                    </div>

                    <div class="max-[540px]:text-right">
                        <h3 class="font-bold">Service</h3>
                        <p>
                            <a href="{{ route('my-appointments.create.barber.service',['service_id' => $service->id, 'barber_id' => $barber->id]) }}" class="text-blue-700 hover:underline">
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
                        <a href="{{ route('my-appointments.create.date',['service_id' => $service->id, 'barber_id' => $barber->id, 'comment' => $comment]) }}" class="text-blue-700 hover:underline">
                            {{ $startTime->format('Y-m-d G:i') }}
                        </a>
                    </div>

                    <div>
                        <h3 class="font-bold">Duration</h3>
                        <p>{{ $service->duration }} minutes</p>
                    </div>

                    <div class="max-[540px]:text-right">
                        <h3 class="font-bold">Comment</h3>
                        <a href="{{ route('my-appointments.create.date',['service_id' => $service->id, 'barber_id' => $barber->id, 'comment' => $comment]) }}" @class(['text-blue-700 hover:underline', 'italic' => $comment == ''])>
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

                <h2 class="text-lg font-bold mb-2">Everything looks fine?</h2>
                <p>Before confirming and finalizing your appointment be sure to take a second to double check the details on the left. If anything is wrong feel free to click on it to modify it.</p>
            </x-card>

            <x-card class="mb-4 text-center">
                @guest
                    <h2 class="font-bold text-lg mb-4">Are you a returning customer?</h2>

                    <div class="flex items-center gap-2 justify-center mb-8">
                        <x-link-button role="ctaMain" link="{{ route('login') }}">Log in</x-link-button>
                        <p>or</p>
                        <x-link-button role="active" link="{{ route('register') }}">Create an account</x-link-button>
                    </div>

                    <div class=" mx-auto mb-6 flex gap-2 justify-center items-center">
                        <hr class="w-1/4">
                        <p class="text-slate-500">or alternatively</p>
                        <hr class="w-1/4">
                    </div>

                    <h2 class="font-bold text-lg mb-4">Book appointment without account</h2>
                @endguest

                <div class="w-full text-left">
                    <form action="{{ route('my-appointments.store') }}" method="post">
                        @csrf

                        <div class="mb-4">
                            <x-label for="first_name">First name*</x-label>
                            <x-input-field id="first_name" name="first_name" class="w-full confirmInput" :disabled="auth()->user() != null" autoComplete="on" :value="auth()->user()?->first_name" />
                        </div>

                        <div class="mb-4">
                            <x-label for="email">Email*</x-label>
                            <x-input-field type="email" id="email" name="email" class="w-full confirmInput" :disabled="auth()->user() != null" autoComplete="on" :value="auth()->user()?->email" />
                        </div>

                        <div class="flex gap-2 items-center mb-2">
                            <x-input-field type="checkbox" name="details_checkbox" id="details_checkbox" class="confirmInput"/>
                            <label for="details_checkbox">Yes, the appointment details match to my expectations.*</label>
                        </div>

                        <div class="flex gap-2 items-center mb-4">
                            <x-input-field type="checkbox" name="policy_checkbox" id="policy_checkbox" class="confirmInput"/>
                            <label for="policy_checkbox">I have read and I accept the Terms & conditions and the Data privacy.*</label>
                        </div>

                        <div>
                            <x-button role="ctaMain" :full="true" id="confirmButton" :disabled="true">Confirm appointment</x-button>
                        </div>
                    </form>
                </div>
            </x-card>            
        </div>

        
    </div>

    <script>
        
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('.confirmInput');
            const button = document.getElementById('confirmButton');

            button.disabled = true;

            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    enableButtonIfInputsFilled(button,inputs,inputs);
                });
            });
        });
    </script>
</x-user-layout>