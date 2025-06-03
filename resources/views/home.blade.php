<x-layout>
    <div class="h-screen w-full bg-black text-white flex flex-col justify-between items-center text-center">
        <div></div>
        <div>
            Perneczky Barber Shop
            <x-link-button :link="route('my-appointments.create')" role="ctaMain">BOOK AN APPOINTMENT</x-link-button>
        </div>
        <div></div>
    </div>

    <x-container class="flex gap-4">
        <div>
            <h2 class="font-black text-2xl mb-6">
                Hi! We are very glad you're here with us!
            </h2>
            <p class="mb-2">
                At Perneczky Brothers, we don't just cut the most precise hair, but we create experiences that give you the confidence and freshness to conquer the world.
            </p>
            <p>
                Come into our cozy sanctuary and let Budapest's coolest team work their magic. Whether it's a classic cut or a new look, we're here to help with everything.
            </p>
            <p>
                Book an appointment today, and let's create something special! See you soon!
            </p>
        </div>
        <div class="flex-shrink-0">
            <img src="{{ asset('logo/perneczky_circle.png') }}" alt="Perneczky BarberShop logo" class=" h-56 w-56">
        </div>
    </x-container>

    <x-header>
        Services
    </x-header>

    <x-container>
        <div class="grid grid-cols-2 max-md:grid-cols-1 gap-8">
            @forelse ($services as $service)
                <div class="border-4 border-blue-700 p-4">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="font-black text-xl">{{ $service->name }}</h2>
                        <p class="text-xl w-fit">{{ $service->price }}&nbsp;Ft</p>
                    </div>
                    <p>Estimated duration: {{ $service->duration }} minutes</p>
                </div>
                
            @empty
                
            @endforelse
        </div>
    </x-container>

    <x-header>
        Barbers
    </x-header>

    <x-container>
        <div class="flex flex-wrap justify-center gap-8">
            @forelse ($barbers as $barber)

                    <x-card class="shadow-xl p-8 text-center">
                        <img src="{{ $barber->user->pfp_path ? asset('storage/pfp/' .  $barber->user->pfp_path) : asset('pfp/pfp_blank.png') }}" alt="{{$barber->display_name ?? $barber->user->first_name}}" class=" h-56 rounded-md mb-4 hover:scale-105 hover:shadow-md transition-all">
                        <h2 class="font-bold text-xl mb-4">
                            {{ $barber->display_name ?? $barber->user->first_name }}
                        </h2>
                        <div class="flex justify-center">
                            <x-link-button role="ctaMain" class="w-fit" :link="route('my-appointments.create.service',['barber_id' => $barber])">
                                BOOK NOW
                            </x-link-button>
                        </div>
                    </x-card>
                
            @empty
                
            @endforelse
        </div>
    </x-container>

    <x-header>
        Location
    </x-header>

    <x-container class="grid grid-cols-2 gap-8">
        <div class="w-full h-96 shadow-2xl">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d168.51367156370375!2d19.076890544242058!3d47.485651873511074!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4741ddc6dd76e7b1%3A0x4d5f1e74a8e65127!2sCorvin%20Barber%20Shop!5e0!3m2!1shu!2shu!4v1698797075447!5m2!1shu!2shu" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div>
            <div class="border-4 border-blue-700 p-4 mb-4">
                <img src="" alt="PERNECZKY BarberShop - Corvin">
                <h2 class="text-2xl font-black mb-2">PERNECZKY Barber Shop - Corvin</h2>
                <p class="mb-1">H-1082 Budapest</p>
                <p>Corvin sétány 5.</p>
            </div>
            <div>
                <h2 class="text-2xl font-black mb-2">Approaching</h2>
                <p>Our store is only a 5-minute walk away from the Corvin-negyed or Semmelweis Klinikák stops of the M3 subway, and from the Corvin-negyed stop of trams 4-6 too!</p>
            </div>
        </div>
        
    </x-container>

    <footer class=" py-12 bg-[#0f0f0f] text-white">
        <div class="max-w-6xl mx-auto flex justify-between">
            <div>
                <h2 class="text-2xl font-black mb-4">Contact</h2>
                <ul class="*:mb-1 mb-4">
                    <li>Tel: <a href="tel:+36704056079">+36 70 405 6079</a></li>
                    <li>Email: <a href="mailto:perneczkybarbershop@gmail.com">perneczkybarbershop@gmail.com</a></li>
                    <li>Address: 1082 Budapest, Corvin sétány 5.</li>
                </ul>
                <div class="flex gap-2">
                    <a href="">
                        <img src="" alt="Instagram">
                    </a>
                    <a href="">
                        <img src="" alt="Facebook">
                    </a>
                    <a href="">
                        <img src="" alt="Tiktok">
                    </a>
                </div>

            </div>
            <div class="text-right">
                <h2 class="text-2xl font-black mb-4">Opening hours</h2>
                <ul class="*:mb-1">
                    <li>Mo-Sa: 10:00-20:00</li>
                    <li>Su: 10:00-18:00</li>
                    <li>T&C</li>
                </ul>
            </div>
        </div>
    </footer>
</x-layout>