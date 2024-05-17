<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    //
    public function index(Request $request)
    {
        $cars = Car::where('active', false)->get();
        return response()->json(['cars' => $cars]);
    }
}
