<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Barber;
use App\Models\Service;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // CREATING THE USERS
        User::factory()->create([
            'first_name' => 'Bence',
            'last_name' => 'Bacsa',
            'date_of_birth' => '2000-03-18',
            'tel_number' => '+36706390318',
            'email' => 'bacsabence00@gmail.com'
        ]);
        User::factory(50)->create();

        // CREATING THE BARBERS
        $users = User::all()->shuffle();
        for ($i=0; $i < 5; $i++) { 
            if ($i % 2 == 0) {
                Barber::factory()->create([
                    'user_id' => $users->pop()->id
                ]);
            } else {
                Barber::factory()->create([
                    'user_id' => $users->pop()->id,
                    'display_name' => fake()->firstNameMale()
                ]);
            }
        }

        // CREATING THE SERVICES
        $services = [
            [
                'name' => 'TIMEOFF',
                'price' => 0,
                'duration' => 0
            ],
            [
                'name' => 'Haircut',
                'price' => 7000,
                'duration' => 45
            ],
            [
                'name' => 'Skinfade',
                'price' => 8000,
                'duration' => 45
            ],
            [
                'name' => 'Beard trimming',
                'price' => 5000,
                'duration' => 30
            ],
            [
                'name' => 'Haircut & Beard trimming',
                'price' => 10000,
                'duration' => 60
            ],
            [
                'name' => 'Skinfade & Beard trimming',
                'price' => 11000,
                'duration' => 60
            ],
            [
                'name' => 'One-length haircut',
                'price' => 5000,
                'duration' => 15
            ],
            [
                'name' => 'One-length haircut & Beard trimming',
                'price' => 8000,
                'duration' => 30
            ],
            [
                'name' => 'Shave (with hot towel)',
                'price' => 7000,
                'duration' => 30
            ],
            [
                'name' => 'Haircut & Shave (Barber Treatment)',
                'price' => 12000,
                'duration' => 75
            ],
            [
                'name' => 'Haircut & Beard trimming & Beard Dyeing',
                'price' => 16000,
                'duration' => 90
            ],
            [
                'name' => 'Beard trimming & Beard dyeing',
                'price' => 9000,
                'duration' => 90
            ],
            [
                'name' => 'Creative haircut (with scissors only)',
                'price' => 9000,
                'duration' => 60
            ],
            [
                'name' => 'Hair colouring',
                'price' => 10000,
                'duration' => 45
            ],
            [
                'name' => 'Kids haircut',
                'price' => 6000,
                'duration' => 30
            ]            
        ];
        foreach ($services as $service) {
            Service::create([
                'name' => $service['name'],
                'price' => $service['price'],
                'duration' => $service['duration']
            ]);
        }

        // CREATING THE APPOINTMENTS
        $timeslots = [];
        for ($h=10; $h < 20; $h++) { 
            for ($m=0; $m < 60; $m+=15) {
                $m == 0 ? $m = '00' : $m;
                $timeslots[] = $h . $m;
            }
        }

        for ($i=0; $i < 200; $i++) { 
            $randomUser = $users->shuffle()->first();
            $randomBarber = Barber::inRandomOrder()->first();
            $randomService = Service::where('id','!=',1)->inRandomOrder()->first();

            $date = Carbon::today()->addDays(rand(-7,30));
            $time = $timeslots[array_rand($timeslots)];

            $app_start_time = Carbon::parse($date->format('Y-m-d') . ' ' . $time);
            $app_end_time = Carbon::parse($app_start_time)->addMinutes($randomService->duration);

            Appointment::factory()->create([
                'user_id' => $randomUser->id,
                'barber_id' => $randomBarber->id,
                'service_id' => $randomService->id,
                'app_start_time' => $app_start_time,
                'app_end_time' => $app_end_time,
                'price' => $randomService->price
            ]);
        }
        
    }
}
