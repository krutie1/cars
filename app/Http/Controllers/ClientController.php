<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    //
    public function create(Request $request)
    {
//        dd('tut');
        /*try {*/
            $validated = $request->validate([
                'phone_number' => ['required', 'regex:/^(?:\+?7|8)\d{10}$/', 'unique:clients'],
                'full_name' => 'required'
            ]);

            $client = Client::create($validated);

//            return redirect()->route('clients.index')
//                ->with('success', 'Product created successfully.' . $client->phone_number);
            return response()->json([
                'success' => true,
                'message' => 'Client created successfully',
                'client' => $client
            ]);
        /*} catch (ValidationException $th) {
            return new JsonResponse($th->validator->errors(), 422);
        }*/
    }

    public function index(Request $request)
    {
        return view('clients');
    }
}
