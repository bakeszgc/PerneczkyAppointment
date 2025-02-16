<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{$title ?? ""}}Perneczky BarberShop</title>
        @vite('resources/css/app.css')
        <script src="https://cdn.tailwindcss.com"></script>
        </style>
    </head>
    <body class="bg-gradient-to-r from-indigo-100 from-10% via-sky-100 via-30% to-emerald-200 to-90% text-slate-700">
        <nav class="h-12 bg-black flex justify-between items-center p-4 text-white mb-14">
            <img src="https://perneczkybarbershop.hu/pictures/logos/perneczky_circle.png" alt="Perneczky BarberShop" class="absolute left-1/2 h-20 -translate-x-10 top-2">
            <div>
                Welcome, {{auth()->user()->first_name ?? 'Guest'}}!
            </div>
            <div>
                @auth
                    <form action="{{route('logout')}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button>Sign Out</button>
                    </form>
                @else
                    <a href="{{route('login')}}">Sign In</a>
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
