<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ ($title ? $title . " - " : "") . env('APP_NAME') }}</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('logo/favicon.ico') }}">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @cookieconsentscripts
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@800&display=swap');
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap');

            *{
                font-family: 'Poppins', sans-serif;
                scroll-behavior: smooth;
            }

            .bar{
                display: block;
                width: 25px;
                height: 3px;
                margin: 5px auto;
                -webkit-transition: all 0.3s ease-in-out;
                transition: all 0.3s ease-in-out;
                background-color: white;
            }

            .w-1\/8{
                width: 12.5%;
            }

            .w-18{
                width: 4.5rem;
            }

            .scroll {
                filter: drop-shadow(0px 0px 30px black);
                animation: down 1.5s infinite;
                -webkit-animation: down 1.5s infinite;
            }

            @keyframes down {
                0% {
                    transform: translate(0);
                }
                20% {
                    transform: translateY(15px);
                }
                40% {
                    transform: translate(0);
                }
            }

            @-webkit-keyframes down {
                0% {
                    transform: translate(0);
                }
                20% {
                    transform: translateY(15px);
                }
                40% {
                    transform: translate(0);
                }
            }

            #logo{
                position: absolute;
                top: 10px;
                left: 50%;
                transform:translateX(-50%);
                z-index: 40;
            }

            #navLinks{
                transform: translateX(108px);
            }

            .parallax {
                text-shadow: 0 4px 10px rgba(0,0,0,0.6);
                overflow: hidden;
                position: relative;
            }

            .parallax::before {
                position: absolute;
                content: "";
                left: 0;
                width: 100%;
                background-size: cover;
                background-position: center center;
                transform: translateZ(0);
                will-change: transform;
                z-index: -1;
            }

            .bg-home::before {
                background-image: url('{{ asset('pictures/interior.jpeg') }}');
                top: 5%;
                height: 120%;
            }

            .bg-barber::before {
                background-image: url({{ asset('pictures/barbers.jpg') }});
                top: -80%;
                height: 200%;
            }

            .bg-service::before {
                background-image: url({{ asset('pictures/services.jpeg') }});
                top: -75%;
                height: 200%;
            }

            .bg-location::before {
                background-image: url({{ asset('pictures/location.jpg') }});
                top: -75%;
                height: 200%;
            }

            @media (prefers-reduced-motion: no-preference) {
                .parallax::before {
                    transform: translateY(var(--scroll, 0));
                    transition: transform 0.1s linear;
                }
            }

            @media not all and (min-width: {{ App::getLocale() == 'en' ? '1024' : '1280' }}px) {
                .hamburger{
                    display: block;
                }
                .hamburger.active .bar:nth-child(2){
                    opacity: 0;
                }
                .hamburger.active .bar:nth-child(1){
                    transform: translateY(8px) rotate(45deg);
                }
                .hamburger.active .bar:nth-child(3){
                    transform: translateY(-8px) rotate(-45deg);
                }
                #navLinks{
                    transform: translateX(0px);
                }
            }
        </style>
    </head>

    <body class="bg-slate-50 text-black">
        <nav @class([
            'h-12 bg-black py-2 px-4 text-white font-extrabold w-full fixed z-40 drop-shadow-lg',
            'max-lg:px-0' => App::getLocale() == 'en',
            'max-xl:px-0' => App::getLocale() == 'hu'
        ]) id="navbar">
            <a href="#home">
                <img src="{{ asset('logo/perneczky_circle.png') }}" alt="{{ env('APP_NAME') }}" id="logo" class="h-20">
            </a>

            <div class="hamburger hidden cursor-pointer w-fit ml-4 z-40">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>

            <div @class([
                'flex justify-between items-center gap-4 nav-menu',
                'max-lg:flex-col max-lg:translate-y-2.5 max-lg:bg-[#0f0f0f] max-lg:pt-16 max-lg:pb-6 max-lg:-translate-x-full' => App::getLocale() == 'en',
                'max-xl:flex-col max-xl:translate-y-2.5 max-xl:bg-[#0f0f0f] max-xl:pt-16 max-xl:pb-6 max-xl:-translate-x-full' => App::getLocale() == 'hu'
            ]) id="nav-menu">

                @auth                    
                    <ul @class([
                        'flex items-center gap-2',
                        'max-lg:flex-col max-lg:gap-4' => App::getLocale() == 'en',
                        'max-xl:flex-col max-xl:gap-4' => App::getLocale() == 'hu',
                    ])>
                        <li>
                            {{ __('home.welcome') . ', ' . (auth()->user()?->barber->display_name ?? auth()->user()->first_name ?? __('home.guest'))}}!
                        </li>

                        @if (auth()->user()->barber ?? false)
                            <li>
                                @if ($currentView !== 'barber')
                                    <a href="{{ route('appointments.index') }}" class=" bg-slate-100 text-slate-800 py-1 px-2 rounded-md hover:bg-slate-300 transition-all">
                                        {{ __('home.barber_view') }}
                                    </a>
                                @else
                                    <a href="{{ route('my-appointments.index') }}" class=" bg-slate-100 text-slate-800 py-1 px-2 rounded-md hover:bg-slate-300 transition-all">
                                        {{ __('home.customer_view') }}
                                    </a>
                                @endif
                            </li>
                        @endif

                        @if (auth()->user()->is_admin ?? false)
                            <li>
                                <a href="{{ route('admin') }}" class=" bg-slate-100 text-slate-800 py-1 px-2 rounded-md hover:bg-slate-300 transition-all">
                                    {{ __('home.admin_dashboard') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                @else
                    <ul>
                        <x-switch-lang />
                    </ul>

                    <ul @class([
                        'flex items-center gap-4',
                        'lg:mr-12 max-lg:gap-2 max-lg:flex-col' => strtolower(App::getLocale()) == 'en',
                        'xl:mr-6 max-xl:gap-2 max-xl:flex-col' => strtolower(App::getLocale()) == 'hu'
                    ]) id="navLinks">
                        <li>
                            <a href="#about" class="hover:text-blue-400 transition-all navLink">
                                {{ __('home.about_us') }}
                            </a>
                        </li>
                        <li>
                            <a href="#services" class="hover:text-blue-400 transition-all navLink">
                                {{ __('home.services') }}
                            </a>
                        </li>
                        <li>
                            <a href="#barbers" class="hover:text-blue-400 transition-all navLink">
                                {{ __('home.barbers') }}
                            </a>
                        </li>
                        <li @class(['w-18',
                            'max-lg:hidden' => strtolower(App::getLocale()) == 'en',
                            'max-xl:hidden' => strtolower(App::getLocale()) == 'hu'
                        ])></li>
                        <li>
                            <a href="#location" class="hover:text-blue-400 transition-all navLink">
                                {{ __('home.location') }}
                            </a>
                        </li>
                        <li>
                            <a href="#contact" class="hover:text-blue-400 transition-all navLink">
                                {{ __('home.contact') }}
                            </a>
                        </li>
                        <li>
                            <a href="#opening-hours" class="hover:text-blue-400 transition-all navLink">
                                {{ __('home.opening_hours') }}
                            </a>
                        </li>
                    </ul>
                @endauth

                <ul @class([
                    'flex items-center gap-2',
                    'max-lg:flex-col' => strtolower(App::getLocale()) == 'en',
                    'max-xl:flex-col' => strtolower(App::getLocale()) == 'hu'
                ])>
                    @auth
                        <li>
                            <a href="{{ route('my-appointments.index') }}" class="py-1 px-2 rounded-md hover:bg-blue-700 transition-all">
                                {{ __('home.my_appointments') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('users.show',auth()->user()) }}" class="py-1 px-2 rounded-md hover:bg-blue-700 transition-all">
                                {{ __('home.account_settings') }}
                            </a>
                        </li>
                        <li>
                            <form action="{{route('logout')}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="bg-slate-100 text-slate-800 py-1 px-2 rounded-md hover:bg-slate-300 transition-all">
                                    {{ __('home.sign_out') }}
                                </button>
                            </form>
                        </li>
                    @else
                        <div @class([
                            'flex items-center gap-1',
                            'max-lg:flex-col' => strtolower(App::getLocale()) == 'en',
                            'max-xl:flex-col' => strtolower(App::getLocale()) == 'hu'
                        ])>
                            <a href="{{ route('register') }}" class="py-1 px-2 rounded-md hover:bg-blue-700 transition-all">
                                {{ __('home.sign_up') }}
                            </a>
                            <a href="{{route('login')}}" class="bg-slate-100 text-slate-800 py-1 px-2 rounded-md hover:bg-slate-300 transition-all">
                                {{ __('home.sign_in') }}
                            </a>
                        </div>                        
                    @endauth
                </ul>
            </div>
            
        </nav>

        <div class="z-10 max-md:text-sm max-lg:text-base relative">

            {{$slot}}
            
        </div>

        @cookieconsentview
        
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const hamburger = document.querySelector(".hamburger");
                const navMenu = document.querySelector("#nav-menu");
                const navLinks = document.querySelectorAll('.navLink');

                hamburger.addEventListener("click", function() {                    
                    toggleNavMenu(navMenu, hamburger);
                });

                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        closeNavMenu(navMenu, hamburger);
                    });
                });
            });

            document.addEventListener("scroll", () => {
                document.querySelectorAll(".parallax").forEach(el => {
                    const speed = -0.2; // smaller = slower parallax
                    const offset = window.scrollY - el.offsetTop;
                    el.style.setProperty("--scroll", `${offset * speed}px`);
                });
            });

            function toggleNavMenu (navMenu, hamburger) {
                if (!navMenu.classList.contains("transition-all")) { 
                    navMenu.classList.add("transition-all");
                    hamburger.classList.toggle("active");
                    navMenu.classList.toggle("max-{{ strtolower(App::getLocale()) == 'en' ? 'lg' : 'xl' }}:-translate-x-full");
                } else { 
                    closeNavMenu(navMenu, hamburger);
                }
            }

            function closeNavMenu(navMenu, hamburger) {
                hamburger.classList.toggle("active");
                navMenu.classList.toggle("max-{{ strtolower(App::getLocale()) == 'en' ? 'lg' : 'xl' }}:-translate-x-full");
                timeout = setTimeout(() => {
                    navMenu.classList.remove("transition-all");
                }, 300);
            }
            
        </script>
    </body>
</html>