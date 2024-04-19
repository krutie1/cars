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
            'full_name' => 'required'
        ], [
            'unique' => 'Пользователь с таким номером телефона уже существует',
        ]);

        $client = Client::create($validated);
        return response()->json([
            'success' => true,
            'message' => 'Добавление клиента завершено успешно',
            'client' => $client
        ]);
    }

    public function index(Request $request)
    {
        $clients = Client::orderBy('id', 'desc')->paginate(12);
        return view('clients', compact('clients'));
    }

    public function destroy(Request $request, $phone_number)
    {
        $client = Client::firstWhere('phone_number', $phone_number);

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Клиент не найден',
            ], Response::HTTP_NOT_FOUND);
        }

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

    public function show(Request $request, $id)
    {
    }

    public function update(Request $request, $id)
    {
    }

}
