<x-layout>
    <div id="home"></div>
    <div class="h-svh w-full text-white flex flex-col justify-between items-center text-center bg-home parallax">
        <div class="h-12"></div>
        <div class="flex flex-col items-center md:gap-4">
            <img src="{{ asset('logo/perneczky_barbershop_corvin.svg') }}" alt="{{ env('APP_NAME') }} Corvin" class="h-36 max-h-[20vh] max-w-[80%] drop-shadow-2xl">

            <a href="{{ route('my-appointments.create') }}" class="text-2xl max-lg:text-xl max-md:text-lg font-bold p-4 max-lg:p-3 max-md:p-2 rounded-lg bg-[#0018d5] hover:bg-[#0f0f0f] transition-all shadow-2xl max-lg:mb-2">
                {{ __('home.book_your_appointment') }}
            </a>

            <a href="tel:{{ str_replace(' ','',env('COMPANY_PHONE')) }}" class="flex gap-2 items-center hover:text-blue-500 drop-shadow-2xl transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd" />
                </svg>
                <h2 class="font-black text-xl">{{ env('COMPANY_PHONE') }}</h2>
            </a>
        </div>
        <div class="scroll">
            <a href="#about">
                <img src="{{ asset('design/scroll_icon.svg') }}" alt="Scroll icon" class="h-12 max-h-[8vh] mb-6">
            </a>
        </div>
    </div>

    <div id="about" class="-translate-y-12"></div>
    <x-container class="flex max-md:flex-col gap-8">
        
        <div class="*:text-justify">
            <h2 class="font-black text-2xl mb-6">
                {{ __('home.about_us_title') }}
            </h2>
            <p class="mb-2">
                {{ __('home.about_us_p1') }}
            </p>
            <p class="mb-2">
                {{ __('home.about_us_p2') }}
            </p>
            <p>
                {{ __('home.about_us_p3') }}
            </p>
        </div>
        <div class="flex-shrink-0">
            <img src="{{ asset('logo/perneczky_circle.png') }}" alt="{{ env('APP_NAME') }} logo" class=" h-56 w-56 max-md:mx-auto">
        </div>
    </x-container>

    <x-header class="bg-service" bgId="services">        
        {{ __('home.services') }}
    </x-header>

    <x-container>
        <div class="grid grid-cols-2 max-md:grid-cols-1 gap-6 max-lg:gap-4">
            @forelse ($services as $service)
                <a href="{{ route('my-appointments.create.barber.service',['service_id' => $service->id]) }}">
                    <div class="rounded-md border-2 border-[#0018d5] p-4 h-full hover:bg-[#0018d5] hover:text-white hover:shadow-2xl transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <h2 class="font-black text-lg max-lg:text-base">{{ $service->getName() }}</h2>
                            <p class="text-lg max-lg:text-base min-w-24 w-fit text-right">{{ number_format($service->price,thousands_separator: ' ') }}&nbsp;HUF</p>
                        </div>
                        <p class="text-base max-lg:text-sm">{{ __('home.estimated_duration') }}: {{ $service->duration }} {{ __('home.minutes') }}</p>
                    </div>
                </a>
            @empty
                <x-empty-card class="col-span-2 max-md:col-span-1">
                    <p>
                        {{ __('home.services_empty') . ' ' . __('home.please_check_back_later') }}
                    </p>
                </x-empty-card>
            @endforelse
        </div>
    </x-container>

    <x-header class="bg-barber" bgId="barbers">
        {{ __('home.barbers') }}
    </x-header>

    <x-container>
        <div class="grid grid-cols-3 max-md:grid-cols-2 gap-8 max-lg:gap-4">
            @forelse ($barbers as $barber)

                <x-card class="flex flex-col justify-between shadow-xl p-8 max-sm:p-4 text-center">
                    <div>
                        <a href="{{ route('my-appointments.create.barber.service',['barber_id' => $barber]) }}">
                            <x-barber-picture :barber="$barber" />
                        </a>

                        <p class=" mt-4 max-md:text-xs">{{ $barber->description }}</p>
                    </div>

                    <div class="flex justify-center mt-4">
                        <x-link-button role="ctaMain" class="w-fit" :link="route('my-appointments.create.barber.service',['barber_id' => $barber])">
                            {{ __('home.book_now') }}
                        </x-link-button>
                    </div>
                </x-card>
            
            @empty

                <x-empty-card class="col-span-3 max-md:col-span-2">
                    <p>{{ __('home.barbers_empty') . ' ' . __('home.please_check_back_later') }}</p>
                </x-empty-card>
                
            @endforelse
        </div>
    </x-container>

    
    <x-header class="bg-location" bgId="location">
        {{ __('home.location') }}
    </x-header>

    <x-container class="grid grid-cols-2 max-md:grid-cols-1 gap-8">
        
        <div>
            <div class="border-2 border-[#0018d5] rounded-md p-4 mb-8">
                <img src="{{ asset('pictures/corvin.jpeg') }}" alt="{{ env('APP_NAME') }} - Corvin" class="mb-4">
                <h2 class="text-2xl max-lg:text-lg font-black mb-2">{{ env('APP_NAME') }} - Corvin</h2>
                <p class="mb-1">{{ explode(',',env('STORE_ADDRESS'))[0] }}</p>
                <p>{{ explode(',',env('STORE_ADDRESS'))[1] }}</p>
            </div>
            <div class="px-4">
                <h2 class="text-2xl max-lg:text-lg font-black mb-2">{{ __('home.approaching') }}</h2>
                <p class="text-justify">{{ __('home.approaching_p') }}</p>
            </div>
        </div>

        <div class="w-full h-full min-h-[500px] max-lg:min-h-[300px] shadow-2xl">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d168.51367156370375!2d19.076890544242058!3d47.485651873511074!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4741ddc6dd76e7b1%3A0x4d5f1e74a8e65127!2sCorvin%20Barber%20Shop!5e0!3m2!1shu!2shu!4v1698797075447!5m2!1shu!2shu" width="100%" height="100%" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        
    </x-container>

    <div id="contact" class="-translate-y-12"></div>
    <div id="opening-hours" class="-translate-y-12"></div>
    <footer class="py-12 max-md:py-8 bg-[#0f0f0f] text-white">        
        <div class="max-w-6xl mx-auto mb-4 px-8 max-lg:px-4 flex max-sm:flex-col justify-between gap-8">
            <div>
                <h2 class="text-2xl max-lg:text-lg font-black mb-2">
                    {{ __('home.contact') }}
                </h2>
                <ul class="*:mb-1 mb-4">
                    <li>Tel: <a href="tel:{{ str_replace(' ','',env('COMPANY_PHONE')) }}" class="hover:text-blue-500 transition-all">
                        {{ env('COMPANY_PHONE') }}
                    </a></li>
                    <li>Email: <a href="mailto:{{ env('COMPANY_MAIL') }}" class="hover:text-blue-500 transition-all">
                        {{ env('COMPANY_MAIL') }}
                    </a></li>
                    <li>{{ __('home.address') }}: 1082 Budapest, Corvin sétány 5.</li>
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
            
            <div class="sm:text-right">
                <h2 class="text-2xl max-lg:text-lg font-black mb-2 min-w-fit">{{ __('home.opening_hours') }}</h2>
                <ul>
                    <li class="mb-1">{{ __('home.monday_short') . '-' . __('home.saturday_short') }}: 10:00-20:00</li>
                    <li class="mb-4">{{ __('home.saturday_short') }}: 10:00-18:00</li>
                    <li class="mb-1">
                        <a href="{{ route('cookies') }}" class="hover:text-blue-500 transition-all">
                            {{ __('home.cookie_policy') }}
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('privacy') }}" class="hover:text-blue-500 transition-all">
                            {{ __('home.privacy_policy') }}
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('terms') }}" class="hover:text-blue-500 transition-all">
                            {{ __('home.terms_and_conditions') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>        
    </footer>
</x-layout>