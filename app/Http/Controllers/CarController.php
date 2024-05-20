<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CarController extends Controller
{
    //
    public function index(Request $request)
    {
        $cars = Car::where('active', false)->get();
        return response()->json(['cars' => $cars]);
    }

    public function update(Request $request, Visit $visit)
    {
        $carId = $request->input('car_id');
        $newCar = Car::find($carId);

        if (!$newCar) {
            return response()->json([
                'success' => false,
                'message' => 'Машина с указанным ID не найдена',
            ], Response::HTTP_NOT_FOUND);
        }

        $car = $visit->car;
        $car->active = false;
        $car->save();

        $newCar->active = true;
        $newCar->save();

        $visit->car_id = $carId;
        $visit->save();

        return response()->json([
            'success' => true,
            'message' => 'Данные успешно изменены'
        ]);
    }
}
