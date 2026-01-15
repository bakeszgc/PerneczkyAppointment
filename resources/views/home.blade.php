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
    <x-container>
        <div class="grid grid-cols-2 max-md:grid-cols-1 gap-x-8 gap-y-16 items-start mb-16">

            <x-main-card class="max-md:order-2">
                <p class="mb-4">
                    {{ __('home.about_us_p1') }}
                </p>
                <p>
                    {{ __('home.about_us_p2') }}
                </p>
            </x-main-card>

            <div class="rounded-md opacity-0 translate-x-1/2 transition-all duration-500 ease-out slide-from-right max-md:order-1">
                <h3 class="font-black text-4xl max-lg:text-3xl max-md:text-center">
                    {{ __('home.about_us_title_1') }}<br class="max-md:hidden">
                    {{ __('home.about_us_title_2') }}<br>
                    {{ __('home.about_us_title_3') }}<br class="max-md:hidden">
                    {{ __('home.about_us_title_4') }}<br>
                </h3>
            </div>

            <div class="rounded-md opacity-0 -translate-x-1/2 transition-all duration-500 ease-out slide-from-left max-md:order-3">
                <h3 class="font-black text-4xl max-lg:text-3xl text-right max-md:text-center">
                    {{ __('home.book_without_reg_title_1') }}<br>
                    {{ __('home.book_without_reg_title_2') }}
                </h3>
            </div>

            <x-main-card direction="right" class="max-md:order-4">
                <p class="text-justify mb-4">
                    {{ __('home.book_without_reg_p1') }}
                </p>
                <p class="text-justify mb-4">
                    {{ __('home.book_without_reg_p2a') }}
                    <a href="#home" class="font-bold transition-all hover:text-blue-300">{{ __('home.book_your_appointment') }}</a>
                    {{ __('home.book_without_reg_p2b') }}
                </p>
                <p class="text-justify">
                    {{ __('home.book_without_reg_p3') }}
                </p>
            </x-main-card>

            <x-main-card class="max-md:order-6">
                <p class="text-justify mb-4">{{ __('home.regular_p1') }}</p>
                <p class="text-justify mb-4">{{ __('home.regular_p2') }}</p>
                <p class="text-justify">{{ __('home.regular_p3') }}</p>
            </x-main-card>

            <div class="rounded-md opacity-0 translate-x-1/2 transition-all duration-500 ease-out slide-from-right max-md:order-5">
                <h3 class="font-black text-4xl max-lg:text-3xl max-md:text-center">
                    {{ __('home.regular_title_1') }}<br class="max-md:hidden">
                    {{ __('home.regular_title_2') }}<br class="md:hidden">
                    {{ __('home.regular_title_3') }}<br class="max-md:hidden">
                    {{ __('home.regular_title_4') }}
                </h3>
            </div>
        </div>

        <div class="border-dashed border-2 border-[#0018d5] rounded-md p-4 text-justify text-base max-sm:text-sm">
            <h3 class="mb-2 font-black text-xl">{{ __('home.demo_1') }}</h3>
            <p class="mb-2">{{ __('home.demo_2') }} {{ __('home.demo_3') }}</p>
            <p>
                <a href="https://perneczkybarbershop.salonic.hu/" class="text-blue-700 hover:underline">https://perneczkybarbershop.salonic.hu/</a>
            </p>
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
            <div class="border-2 border-[#0018d5] rounded-md p-4 mb-8 text-base">
                <img src="{{ asset('pictures/corvin.jpeg') }}" alt="{{ env('APP_NAME') }} - Corvin" class="mb-4">
                <h2 class="text-2xl max-lg:text-lg font-black mb-2">{{ env('APP_NAME') }} - Corvin</h2>
                <p class="mb-1">{{ explode(',',env('STORE_ADDRESS'))[0] }}</p>
                <p>{{ explode(',',env('STORE_ADDRESS'))[1] }}</p>
            </div>
            <div class="px-4">
                <h2 class="text-2xl max-lg:text-lg font-black mb-2">{{ __('home.approaching') }}</h2>
                <p class="text-justify text-base max-sm:text-sm">{{ __('home.approaching_p') }}</p>
            </div>
        </div>

        <div class="w-full h-full min-h-[500px] max-lg:min-h-[300px] shadow-2xl">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d168.51367156370375!2d19.076890544242058!3d47.485651873511074!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4741ddc6dd76e7b1%3A0x4d5f1e74a8e65127!2sCorvin%20Barber%20Shop!5e0!3m2!1shu!2shu!4v1698797075447!5m2!1shu!2shu" width="100%" height="100%" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

    </x-container>

    <div id="contact" class="-translate-y-12"></div>
    <div id="opening-hours" class="-translate-y-12"></div>
    <footer class="py-12 max-md:py-8 bg-[#0f0f0f] text-white text-base max-sm:text-sm">
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const leftCards = document.querySelectorAll('.slide-from-left');
            const rightCards = document.querySelectorAll('.slide-from-right');
            checkBothCardTypes(leftCards, rightCards);

            window.addEventListener('scroll', () => {                
                checkBothCardTypes(leftCards, rightCards);
            });
        });

        function checkBothCardTypes(leftCards, rightCards) {
            checkCardToAppear(leftCards, 'left');
            checkCardToAppear(rightCards, 'right');
        }

        function checkCardToAppear(cards, direction) {
            cards.forEach(card => {
                if (card.getBoundingClientRect().top <= window.innerHeight*0.85) {
                    if (direction == 'left') {
                        card.classList.remove('opacity-0','-translate-x-1/2');
                    } else {
                        setTimeout(() => {
                            card.classList.remove('opacity-0','translate-x-1/2');
                        }, 100);
                    }
                }
            });
        }
    </script>
</x-layout>