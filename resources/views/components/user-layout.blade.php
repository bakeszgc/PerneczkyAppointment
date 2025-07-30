<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{$title ?? ""}}Perneczky BarberShop</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('logo/favicon.ico') }}">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@800&display=swap');
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap');
            *{
                font-family: 'Poppins', sans-serif;
                scroll-behavior: smooth;
            }
            .rellax-bg {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 120vh;
                z-index: -1;
                background-position: center center;
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

            @media not all and (min-width: 1024px) {
                .rellax-bg {
                    display: none;
                }
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
            }
            

        </style>
    </head>

    <body class="bg-slate-50 text-slate-800">
        <nav class="h-12 bg-black py-2 px-4 max-lg:px-0 text-white font-extrabold w-full fixed z-40 drop-shadow-lg  id="navbar">

            <img src="{{ asset('logo/perneczky_circle.png') }}" alt="Perneczky BarberShop" id="logo" class="absolute left-1/2 h-20 -translate-x-5 top-2 z-50">

            <div class="hamburger hidden cursor-pointer max-lg:block w-fit ml-4 mt-1">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>

            <div class="flex justify-between items-center gap-4 max-lg:flex-col max-lg:translate-y-2 max-lg:bg-slate-950 max-lg:pt-12 max-lg:pb-6 max-lg:-translate-x-full nav-menu" id="nav-menu">
                <ul class="flex items-center gap-2 max-lg:flex-col">
                    <li>
                        Welcome, {{auth()->user()->barber->display_name ?? auth()->user()->first_name ?? 'Guest'}}!
                    </li>

                    @if (auth()->user()->barber ?? false)
                        <li>
                            @if ($currentView !== 'barber')
                                <a href="{{ route('appointments.index') }}" class=" bg-slate-100 text-slate-800 py-1 px-2 rounded-md hover:bg-slate-300 transition-all">
                                Barber View
                                </a>
                            @else
                                <a href="{{ route('my-appointments.index') }}" class=" bg-slate-100 text-slate-800 py-1 px-2 rounded-md hover:bg-slate-300 transition-all">
                                Customer View
                                </a>
                            @endif
                        </li>
                    @endif

                    @if (auth()->user()->is_admin ?? false)
                        <li>
                            @if ($currentView !== 'admin')
                                <a href="{{ route('admin') }}" class=" bg-slate-100 text-slate-800 py-1 px-2 rounded-md hover:bg-slate-300 transition-all">
                                    Admin Dashboard
                                </a>
                            @else
                                <a href="{{ route('my-appointments.index') }}" class=" bg-slate-100 text-slate-800 py-1 px-2 rounded-md hover:bg-slate-300 transition-all">
                                    Customer View
                                </a>
                            @endif
                            
                        </li>
                    @endif
                </ul>

                <ul class="flex items-center gap-4 max-lg:flex-col">
                    @auth
                        @switch($currentView)
                            @case('barber')
                                <li>
                                    <a href="{{ route('appointments.index') }}" class="py-1 px-2 rounded-md hover:bg-blue-700 transition-all">
                                        Bookings
                                    </a>
                                </li>
                                @break
                            
                            @case('admin')
                                <li>
                                    <a href="{{ route('admin') }}" class="py-1 px-2 rounded-md hover:bg-blue-700 transition-all">
                                        Admin Dashboard
                                    </a>
                                </li>
                            @break
                        
                            @default
                                <li>
                                    <a href="{{ route('my-appointments.index') }}" class="py-1 px-2 rounded-md hover:bg-blue-700 transition-all">
                                        My Appointments
                                    </a>
                                </li>
                                
                        @endswitch
                        <li>
                            <a href="{{ route('users.show',auth()->user()) }}" class="py-1 px-2 rounded-md hover:bg-blue-700 transition-all">
                                Account Settings
                            </a>
                        </li>
                        <li>
                            <form action="{{route('logout')}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="bg-slate-100 text-slate-800 py-1 px-2 rounded-md hover:bg-slate-300 transition-all">Sign Out</button>
                            </form>
                        </li>
                    @else
                        <a href="{{ route('register') }}" class="py-1 px-2 rounded-md hover:bg-blue-700 transition-all">
                            Sign Up
                        </a>
                        <a href="{{route('login')}}" class="bg-slate-100 text-slate-800 py-1 px-2 rounded-md hover:bg-slate-300 transition-all">Sign In</a>
                    @endauth
                </ul>
            </div>
        </nav>
        
        <div class="rellax-bg" style="background-image: url('{{ asset('design/blades.png') }}'); opacity: 0.5;" data-rellax-speed="4"></div>
        <div class="rellax-bg" style="background-image: url('{{ asset('design/combs.png') }}'); opacity: 0.3;" data-rellax-speed="2"></div>
        <div class="rellax-bg" style="background-image: url('{{ asset('design/scissors.png') }}'); opacity: 0.2;" data-rellax-speed="1"></div>
        
        <div class=" max-w-4xl mx-auto px-4 relative z-10 pt-24" x-data="{ showAlert: true }">

            @if (session('success'))
                <div role="alert" class="mb-8 rounded-md border-l-4 border-green-300 bg-green-100 p-4 text-green-700 oppacity-75 flex justify-between" x-show="showAlert">
                    <div>
                        <p class="font-bold">Success!</p>
                        <p>{{session('success')}}</p>
                        
                    </div>
                    <p @click="showAlert = false" class="cursor-pointer rounded-full hover:bg-green-300 transition-all p-1 max-h-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6" >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </p>
                    
                    
                </div>
            @endif

            @if (session('error'))
                <div role="alert" class="mb-8 rounded-md border-l-4 border-red-300 bg-red-100 p-4 text-red-700 oppacity-75 flex justify-between"  x-show="showAlert">
                    <div>
                        <p class="font-bold">Error!</p>
                        <p>{{session('error')}}</p>
                        
                    </div>
                    <p @click="showAlert = false" class="cursor-pointer rounded-full hover:bg-red-300 transition-all p-1 max-h-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6" >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </p>
                </div>
                
            @endif

            {{$slot}}
            
        </div>

        <script>
            const hamburger = document.querySelector(".hamburger");
            const navMenu = document.querySelector("#nav-menu")

            hamburger.addEventListener("click", () =>{
                if (!navMenu.classList.contains("transition-all")) { 
                    navMenu.classList.add("transition-all");
                    hamburger.classList.toggle("active");
                    navMenu.classList.toggle("max-lg:-translate-x-full");
                } else { 
                    hamburger.classList.toggle("active");
                    navMenu.classList.toggle("max-lg:-translate-x-full");
                    timeout = setTimeout(() => {
                        navMenu.classList.remove("transition-all");
                    }, 300);
                }
            })
        </script>
    </body>
</html>