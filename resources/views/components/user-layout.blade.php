<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{$title ?? ""}}Perneczky BarberShop</title>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
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
            @media not all and (min-width: 1024px) {
                .rellax-bg {
                    display: none;
                }
            }
        </style>
    </head>

    <body class="bg-slate-100 text-slate-800">
        <nav class="h-12 bg-black flex justify-between items-center p-4 text-white font-extrabold w-full fixed z-50 drop-shadow-lg" id="navbar">
            <img src="https://perneczkybarbershop.hu/pictures/logos/perneczky_circle.png" alt="Perneczky BarberShop" id="logo" class="absolute left-1/2 h-20 -translate-x-10 top-2">

            <div class="flex items-center gap-4">
                Welcome, {{auth()->user()->barber->display_name ?? auth()->user()->first_name ?? 'Guest'}}!
                @if (auth()->user()->barber ?? false)
                    @if ($currentView !== 'barber')
                        <a href="{{ route('appointments.index') }}" class=" bg-slate-100 text-slate-800 py-1 px-2 rounded-md hover:bg-slate-300 transition-all">
                        Switch to Barber View
                        </a>
                    @else
                        <a href="{{ route('my-appointments.index') }}" class=" bg-slate-100 text-slate-800 py-1 px-2 rounded-md hover:bg-slate-300 transition-all">
                        Switch to Customer View
                        </a>
                    @endif
                @endif
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <div>
                        <a href="{{ route('users.show',auth()->user()) }}" class="py-1 px-2 rounded-md hover:bg-blue-700 transition-all">
                            Account Settings
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('my-appointments.index') }}" class="py-1 px-2 rounded-md hover:bg-blue-700 transition-all">
                            My Appointments
                        </a>
                    </div>
                    <div>
                        <form action="{{route('logout')}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="bg-slate-100 text-slate-800 py-1 px-2 rounded-md hover:bg-slate-300 transition-all">Sign Out</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('register') }}" class="py-1 px-2 rounded-md hover:bg-blue-700 transition-all">
                        Sign Up
                    </a>
                    <a href="{{route('login')}}" class="bg-slate-100 text-slate-800 py-1 px-2 rounded-md hover:bg-slate-300 transition-all">Sign In</a>
                @endauth
            </div>
        </nav>
        
        <div class="rellax-bg" style="background-image: url('{{ asset('design/blades.png') }}'); opacity: 0.5;" data-rellax-speed="4"></div>
        <div class="rellax-bg" style="background-image: url('{{ asset('design/combs.png') }}'); opacity: 0.3;" data-rellax-speed="2"></div>
        <div class="rellax-bg" style="background-image: url('{{ asset('design/scissors.png') }}'); opacity: 0.2;" data-rellax-speed="1"></div>
        
        <div class="max-w-4xl mx-auto px-4 relative z-10 pt-24">

            @if (session('success'))
                <div role="alert" class="mb-8 rounded-md border-l-4 border-green-300 bg-green-100 p-4 text-green-700 oppacity-75">
                    <p class="font-bold">Success!</p>
                    <p>{{session('success')}}</p>
                </div>
            @endif
            @if (session('error'))
                <div role="alert" class="mb-8 rounded-md border-l-4 border-red-300 bg-red-100 p-4 text-red-700 oppacity-75">
                    <p class="font-bold">Error!</p>
                    <p>{{session('error')}}</p>
                </div>
            @endif

            {{$slot}}
            
        </div>
    </body>
</html>
