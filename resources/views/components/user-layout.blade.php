<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{$title ?? ""}}Perneczky BarberShop</title>
        @vite('resources/css/app.css')
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            *{
                font-family: 'Poppins', sans-serif;
            }
        </style>
    </head>
    <body class="bg-slate-100 text-slate-800">
        <nav class="h-12 bg-black flex justify-between items-center p-4 text-white mb-14 font-extrabold">
            <img src="https://perneczkybarbershop.hu/pictures/logos/perneczky_circle.png" alt="Perneczky BarberShop" class="absolute left-1/2 h-20 -translate-x-10 top-2">

            <div class="flex items-center gap-4">
                Welcome, {{auth()->user()->barber->display_name ?? auth()->user()->first_name ?? 'Guest'}}!
                @if (auth()->user()->barber ?? false)
                    <a href="{{ route('appointments.index') }}" class=" bg-slate-100 text-slate-700 py-1 px-2 rounded-md hover:bg-slate-300 transition-all">
                        Switch to Barber View
                    </a>
                @endif
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <div>
                        <a href="{{ route('my-appointments.index') }}" class="py-1 px-2 rounded-md hover:bg-blue-700 transition-all">
                            Profile Settings
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
                    <a href="{{route('login')}}" class="bg-slate-100 text-slate-800 py-1 px-2 rounded-md hover:bg-slate-300 transition-all">Sign In</a>
                @endauth
            </div>
        </nav>

        <div class="max-w-4xl mx-auto px-4">
            @if (session('success'))
                <div role="alert" class="my-8 rounded-md border-l-4 border-green-300 bg-green-100 p-4 text-green-700 oppacity-75">
                    <p class="font-bold">Success!</p>
                    <p>{{session('success')}}</p>
                </div>
            @endif
            @if (session('error'))
                <div role="alert" class="my-8 rounded-md border-l-4 border-red-300 bg-red-100 p-4 text-red-700 oppacity-75">
                    <p class="font-bold">Error!</p>
                    <p>{{session('error')}}</p>
                </div>
            @endif

            {{$slot}}
        </div>
        
        
    </body>
</html>
