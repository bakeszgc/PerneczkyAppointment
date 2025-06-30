<x-layout>
    <div class="h-screen w-full text-white flex flex-col justify-between items-center text-center bg-cover bg-center bg-fixed bg-no-repeat" style="background-image: url('{{ asset('pictures/interior.jpeg') }}');">
        <div class="h-24"></div>
        <div class="flex flex-col items-center gap-8">
            <img src="{{ asset('logo/perneczky_barbershop_corvin.svg') }}" alt="PERNECZKY BarberShop Corvin" class="h-36 drop-shadow-2xl">

            <a href="{{ route('my-appointments.create') }}" class="text-3xl font-bold p-4 rounded-lg bg-[#0018d5] hover:bg-[#0f0f0f] transition-all shadow-2xl">BOOK YOUR APPOINTMENT</a>

            <a href="tel:+36704056079" class="flex gap-2 items-center hover:text-blue-500 drop-shadow-2xl transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd" />
                </svg>
                <h2 class="font-black text-xl">+36 70 405 6079</h2>
            </a>
        </div>
        <div>
            <img src="{{ asset('design/scroll_icon.svg') }}" alt="Scroll icon" class="h-12 mb-4">
        </div>
    </div>

    <x-container class="flex max-md:flex-col gap-8">
        <div class="*:text-justify">
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
            <img src="{{ asset('logo/perneczky_circle.png') }}" alt="Perneczky BarberShop logo" class=" h-56 w-56 max-md:mx-auto">
        </div>
    </x-container>

    <x-header :bgImage="asset('pictures/services.jpeg')">
        Services
    </x-header>

    <x-container>
        <div class="grid grid-cols-2 max-md:grid-cols-1 gap-6">
            @forelse ($services as $service)
                <a href="{{ route('my-appointments.create.barber.service',['service_id' => $service->id]) }}">
                    <div class="rounded-md border-2 border-[#0018d5] p-4 h-full hover:bg-[#0018d5] hover:text-white hover:shadow-2xl transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <h2 class="font-black text-lg">{{ $service->name }}</h2>
                            <p class="text-lg min-w-24 w-fit text-right">{{ number_format($service->price,thousands_separator: ' ') }}&nbsp;Ft</p>
                        </div>
                        <p class="text-base">Estimated duration: {{ $service->duration }} minutes</p>
                    </div>
                </a>
            @empty
                
            @endforelse
        </div>
    </x-container>

    <x-header :bgImage="asset('pictures/barbers.jpg')">
        Barbers
    </x-header>

    <x-container>
        <div class="grid grid-cols-3 gap-8">
            @forelse ($barbers as $barber)

                <x-card class="shadow-xl p-8 text-center">
                    <a href="{{ route('my-appointments.create.barber.service',['barber_id' => $barber]) }}">
                        <x-barber-picture :barber="$barber" />
                    </a>

                    <p class=" mt-4">Valami bemutatkozó szöveg</p>

                    <div class="flex justify-center mt-4">
                        <x-link-button role="ctaMain" class="w-fit" :link="route('my-appointments.create.barber.service',['barber_id' => $barber])">
                            BOOK NOW
                        </x-link-button>
                    </div>
                </x-card>
            
            @empty
                
            @endforelse
        </div>
    </x-container>

    <x-header :bgImage="asset('pictures/location.jpg')">
        Location
    </x-header>

    <x-container class="grid grid-cols-2 max-md:grid-cols-1 gap-8">
        
        <div>
            <div class="border-2 border-[#0018d5] p-4 mb-8">
                <img src="{{ asset('pictures/corvin.jpeg') }}" alt="PERNECZKY BarberShop - Corvin" class="mb-4">
                <h2 class="text-2xl font-black mb-2">PERNECZKY Barber Shop - Corvin</h2>
                <p class="mb-1">H-1082 Budapest</p>
                <p>Corvin sétány 5.</p>
            </div>
            <div class="px-4">
                <h2 class="text-2xl font-black mb-2">Approaching</h2>
                <p class="text-justify">Our store is only a 5-minute walk away from the Corvin-negyed or Semmelweis Klinikák stops of the M3 subway, and from the Corvin-negyed stop of trams 4&#8209;6 too!</p>
            </div>
        </div>

        <div class="w-full h-full min-h-[500px] shadow-2xl">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d168.51367156370375!2d19.076890544242058!3d47.485651873511074!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4741ddc6dd76e7b1%3A0x4d5f1e74a8e65127!2sCorvin%20Barber%20Shop!5e0!3m2!1shu!2shu!4v1698797075447!5m2!1shu!2shu" width="100%" height="100%" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        
    </x-container>

    <footer class=" py-12 bg-[#0f0f0f] text-white">
        <div class="max-w-6xl mx-auto px-8 flex justify-between">
            <div>
                <h2 class="text-2xl font-black mb-4">Contact</h2>
                <ul class="*:mb-1 mb-4">
                    <li>Tel: <a href="tel:+36704056079" class="hover:text-blue-500 transition-all">+36 70 405 6079</a></li>
                    <li>Email: <a href="mailto:perneczkybarbershop@gmail.com" class="hover:text-blue-500 transition-all">perneczkybarbershop@gmail.com</a></li>
                    <li>Address: 1082 Budapest, Corvin sétány 5.</li>
                </ul>
                <div class="flex gap-2 *:*:transition-all *:*:h-10">
                    <a href="https://www.instagram.com/perneczkybarbershop" target="_blank">
                        <img src="{{ asset('logo/instagram.png') }}" alt="Instagram" class="hover:scale-110">
                    </a>
                    <a href="https://www.facebook.com/perneczkybarbershop" target="_blank">
                        <img src="{{ asset('logo/facebook.png') }}" alt="Facebook" class="hover:scale-110">
                    </a>
                    <a href="https://www.tiktok.com/@perneczkybarbershop" target="_blank">
                        <img src="{{ asset('logo/tiktok.png') }}" alt="Tiktok" class="hover:scale-110">
                    </a>
                </div>

            </div>
            <div class="text-right">
                <h2 class="text-2xl font-black mb-4">Opening hours</h2>
                <ul class="*:mb-1">
                    <li>Mo-Sa: 10:00-20:00</li>
                    <li>Su: 10:00-18:00</li>
                    <li><a href="{{ asset('files/perneczky_aszf.pdf') }}" target="_blank" class="hover:text-blue-500 transition-all">T&C</a></li>
                </ul>
            </div>
        </div>
    </footer>
</x-layout>