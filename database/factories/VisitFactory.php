<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
            'start_time' => $this->faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
            'end_time' => $this->faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
            'phone_number' => '8' . $this->faker->numerify('7#########'),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
        ];
    }
}
