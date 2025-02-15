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
    <nav class="h-12 bg-black flex justify-between items-center p-4 text-white">
            <div>
                Signed in as {{auth()->user()->first_name ?? 'Guest'}}
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
        <div class="w-80 h-screen fixed py-4 bg-white flex flex-col justify-between">
            <div>
                <x-menu-block :links="[
                    'Dashboard' => route('dashboard')
                ]" class="mb-4"/>

                <x-menu-block :links="[
                    'Appointments' => route('appointments.index'),
                    'Upcoming Appointments' => route('appointments.upcoming'),
                    'Previous Appointments' => route('appointments.previous'),
                    'Cancelled Appointments' => route('appointments.index'),
                    'Create New Appointment' => route('appointments.create')
                ]" class="mb-4" />

                <x-menu-block :links="[
                    'Profile Settings' => '#'
                ]" class="mb-4"/>
            </div>
            <div>
                <ul>
                    <x-menu-item :link="route('appointments.index')">
                        Logout
                    </x-menu-item>
                </ul>
            </div>
        </div>
        <div class="flex">
            <div class="w-full px-8 mt-4 ml-80">
                {{$slot}}
            </div>
        </div>        
    </body>
</html>
