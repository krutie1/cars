<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Visit;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class VisitSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Get all clients from the database
        $clients = Client::all();

        // Loop through each client and create random visits
        foreach ($clients as $client) {
            // Generate a random number of visits (between 1 and 5)
            $numVisits = $faker->numberBetween(1, 5);

            for ($i = 0; $i < $numVisits; $i++) {
                $startTime = $faker->dateTimeThisMonth();
                $endTime = $faker->dateTimeBetween($startTime, '+7 days');
                $comment = $faker->sentence;
                $cost = $faker->randomNumber(4) * 100; // Random cost between 0 and 9999
                

                $paymentId = $faker->numberBetween(1, 3); // Assuming you have payment IDs from 1 to 10
                $userId = $faker->numberBetween(1, 2); // Assuming you have user IDs from 1 to 20

                // Create a new visit
                Visit::create([
                    'client_id' => $client->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'comment' => $comment,
                    'cost' => $cost,
                    'payment_id' => $paymentId,
                    'user_id' => $userId,
                ]);
            }
        }
    }
}
