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
            'email' => 'bacsabence00@gmail.com',
            'is_admin' => true
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
                'name' => 'SZÜNET',
                'en_name' => 'TIMEOFF',
                'price' => 0,
                'duration' => 0,
                'is_visible' => 0
            ],
            [
                'name' => 'Hajvágás',
                'en_name' => 'Haircut',
                'price' => 7000,
                'duration' => 45
            ],
            [
                'name' => 'Skinfade',
                'en_name' => 'Skinfade',
                'price' => 8000,
                'duration' => 45
            ],
            [
                'name' => 'Szakálligazítás',
                'en_name' => 'Beard trimming',
                'price' => 5000,
                'duration' => 30
            ],
            [
                'name' => 'Hajvágás & szakálligazítás',
                'en_name' => 'Haircut & beard trimming',
                'price' => 10000,
                'duration' => 60
            ],
            [
                'name' => 'Skinfade & szakálligazítás',
                'en_name' => 'Skinfade & beard trimming',
                'price' => 11000,
                'duration' => 60
            ],
            [
                'name' => 'Egyhossz gépi hajvágás',
                'en_name' => 'One-length haircut',
                'price' => 5000,
                'duration' => 15
            ],
            [
                'name' => 'Egyhossz gépi hajvágás & szakálligazítás',
                'en_name' => 'One-length haircut & beard trimming',
                'price' => 8000,
                'duration' => 30
            ],
            [
                'name' => 'Borotválás (forró törölközővel)',
                'en_name' => 'Shave (with hot towel)',
                'price' => 7000,
                'duration' => 30
            ],
            [
                'name' => 'Hajvágás és borotválás (Barber treatment)',
                'en_name' => 'Haircut & shave (Barber treatment)',
                'price' => 12000,
                'duration' => 75
            ],
            [
                'name' => 'Hajvágás & szakálligazítás & szakállfestés',
                'en_name' => 'Haircut & beard trimming & beard dyeing',
                'price' => 16000,
                'duration' => 90
            ],
            [
                'name' => 'Szakálligazítás & szakállfestés',
                'en_name' => 'Beard trimming & beard dyeing',
                'price' => 9000,
                'duration' => 90
            ],
            [
                'name' => 'Kreatív hajvágás (csak ollóval)',
                'en_name' => 'Creative haircut (with scissors only)',
                'price' => 9000,
                'duration' => 60
            ],
            [
                'name' => 'Hajfestés',
                'en_name' => 'Hair colouring',
                'price' => 10000,
                'duration' => 45
            ],
            [
                'name' => 'Gyerek hajvágás',
                'en_name' => 'Kids haircut',
                'price' => 6000,
                'duration' => 30
            ]            
        ];

        foreach ($services as $service) {
            Service::factory()->create([
                'name' => $service['name'],
                'en_name' => $service['en_name'],
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
            $randomService = Service::withoutTimeoff()->inRandomOrder()->first();

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
