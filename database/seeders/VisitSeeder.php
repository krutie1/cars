<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Client;
use App\Models\Visit;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

// Add this import for the Car model

class VisitSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Get all clients from the database
        $clients = Client::all();

        // Get all cars from the database
        $cars = Car::all();

        // Loop through each client and create random visits
        foreach ($clients as $client) {
            // Generate a random number of visits (between 1 and 5)
            $numVisits = $faker->numberBetween(1, 5);

            for ($i = 0; $i < $numVisits; $i++) {
                $startTime = $faker->dateTimeThisMonth();
                $endTime = $faker->dateTimeBetween($startTime, '+7 days');
                $comment = $faker->sentence;
                $cost = $faker->randomNumber(4) * 100; // Random cost between 0 and 9999

                $userId = $faker->numberBetween(1, 2); // Assuming you have user IDs from 1 to 2

                // Get a random car ID
                $carId = $cars->random()->id;

                // Create a new visit
                Visit::create([
                    'client_id' => $client->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'comment' => $comment,
                    'cost' => $cost,
                    'user_id' => $userId,
                    'car_id' => $carId, // Assign the random car ID
                ]);
            }
        }
    }
}
