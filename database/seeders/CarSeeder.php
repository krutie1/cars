<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carNames = ['К1', 'К2', 'К3', 'К4', 'К5', 'М1', 'М2', 'М3', 'М4', 'М5', 'М6', 'М7', 'М8', 'М9', 'М10', 'М11', 'М12', 'М13', 'М14', 'М15', 'М16', 'М17', 'М18', 'М19', 'М20', 'М21', 'М22', 'М23', 'М24', 'М25', 'М26', 'М27', 'М28', 'М29', 'М30'];

        foreach ($carNames as $name) {
            // Create a new car record
            Car::create([
                'name' => $name,
                'active' => false,
            ]);
        }
    }
}
