<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules\Unique;

class ClientController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => ['required', 'regex:/^8\d{10}$/', (new Unique('clients'))->withoutTrashed()],
            'first_name' => 'required',
            'last_name' => 'required',
            'patronymic' => 'nullable'
        ], [
            'unique' => 'Пользователь с таким номером телефона уже существует',
        ]);

        $client = Client::create($validated);


        return response()->json([
            'success' => true,
            'message' => 'Клиент успешно создан',
            'client' => $client
        ]);
    }

    public function index(Request $request)
    {
        $clients = Client::orderBy('id', 'desc')->paginate(12);
        return view('clients', compact('clients'));
    }

    public function destroy(Request $request, Client $client)
    {
        if ($client->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Клиент успешно удалён',
                'client' => $client
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении клиента',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request, $phone_number)
    {
        ;
    }

    public function findByPhoneNumber(Request $request)
    {
        $phone_number = $request->input('phone_number');

        if (empty($phone_number)) {
            $clients = Client::orderBy('id', 'desc')->paginate(12);
        } else {
            $clients = Client::orderBy('id', 'desc')->where('phone_number', $phone_number)->paginate(12);
        }

        return view('clients', compact('clients'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'phone_number' => ['required', 'regex:/^8\d{10}$/', (new Unique('clients'))->withoutTrashed()->ignoreModel($client)],
            'first_name' => 'required',
            'last_name' => 'required',
            'patronymic' => 'nullable',
        ], [
            'unique' => 'Пользователь с таким номером телефона уже существует',
        ]);

        if ($client->update($validated)) {
            return response()->json([
                'success' => true,
                'message' => 'Данные успешно изменены',
                'client' => $client
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при редактировании клиента',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
