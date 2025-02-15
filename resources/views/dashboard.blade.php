<x-layout title="Dashboard - Perneczky BarberShop">
    <x-breadcrumbs :links="[
        'Dashboard' => ''
    ]"/>
    <h1 class="font-bold text-4xl mb-4">Welcome back, NameHere!</h1>
    <div class="grid grid-cols-4 justify-between gap-4 w-full">
        <x-card>
            <h2 class="font-bold text-2xl mb-4">Upcoming Appointments</h2>

            @forelse ($upcomingAppointments as $appointment)
                <div class="flex justify-between items-center mb-2">
                    <div>
                        <h3 class="font-medium text-lg">
                            {{$appointment->user->first_name}} #{{$appointment->id}}</h3>
                        <p>{{\Illuminate\Support\Carbon::parse($appointment->app_start_time)->diffForHumans()}}
                        </p>
                    </div>
                    <x-link-button role="show" :link="route('appointments.show',['appointment' => $appointment])"/>
                </div>
            @empty
                
            @endforelse

            @if (count($upcomingAppointments) > 0)
                <div>
                    <a href="{{route('appointments.index')}}">All Appointments</a>
                </div>
            @endif
        </x-card>

        <x-card class="">
            <h2 class="font-bold text-2xl mb-4">Your Income</h2>

            <div class="flex justify-between mb-4">
                <div>
                    <h3 class="font-medium text-lg">Today</h3>
                </div>
                <div class="text-right">
                    <h3 class="font-medium text-lg">{{number_format($todayIncome,thousands_separator:' ')}} Ft</h3>
                </div>
            </div>

            <div class="flex justify-between mb-4">
                <div>
                    <h3 class="font-medium text-lg">Past 7 days</h3>
                    <p>Daily average</p>
                </div>
                <div class="text-right">
                    <h3 class="font-medium text-lg">{{number_format($past7DaysIncome,thousands_separator:' ')}} Ft</h3>
                    <p>{{number_format($past7DaysIncome / 7,thousands_separator:' ')}} Ft</p>
                </div>
            </div>

            <div class="flex justify-between">
                <div>
                    <h3 class="font-medium text-lg">Past 30 days</h3>
                    <p>Daily average</p>
                </div>
                <div class="text-right">
                    <h3 class="font-medium text-lg">{{number_format($past30DaysIncome,thousands_separator:' ')}} Ft</h3>
                    <p>{{number_format($past30DaysIncome / 30,thousands_separator:' ')}} Ft</p>
                </div>
            </div>
            
            

        </x-card>
        <x-card>
            3 - Nemtom mit lehetne még iderakni
        </x-card>
        <x-card>
            <h2 class="font-bold text-2xl mb-4">Szfárli Baddadan</h2>
            <iframe width="max-width" height="315" src="https://www.youtube.com/embed/rkjNL4dX-U4?si=wSfw-kagI8CvDKuM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </x-card>
    </div>
</x-layout>