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
            'last_name' => 'nullable',
            'patronymic' => 'nullable'
        ], [
            'unique' => 'Пользователь с таким номером телефона уже существует',
        ]);

        // Check if a client with the same phone number already exists, including soft deleted clients
        $existingClient = Client::where('phone_number', $validated['phone_number'])->first();

        if ($existingClient) {
            return response()->json([
                'success' => false,
                'message' => 'Пользователь с таким номером телефона уже существует',
            ], 400);
        }

        $client = Client::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Клиент успешно создан',
            'client' => $client
        ]);
    }

    public function index(Request $request)
    {
        $clients = Client::with('lastVisit')
            ->withCount([
                'visits' => function ($query) {
                    $query->whereNull('deleted_at')->whereNotNull('payment_date');
                }
            ])
            ->orderBy('id', 'desc')
            ->paginate(7);

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

    }

    public function find(Request $request)
    {
        $searchQuery = $request->input('search_query');

        $clientsExisting = Client::query()->with('lastVisit')->withCount(['visits' => function ($query) {
            $query->whereNull('deleted_at')->whereNotNull('payment_date');
        }]);

        if (empty($searchQuery)) {
            $clients = $clientsExisting->orderBy('id', 'desc')->paginate(7);
        } else {
            $clients = $clientsExisting->where(function ($query) use ($searchQuery) {
                $query->where('phone_number', 'like', '%' . $searchQuery . '%')
                    ->orWhere('first_name', 'like', '%' . $searchQuery . '%')
                    ->orWhere('last_name', 'like', '%' . $searchQuery . '%');
            })->orderBy('id', 'desc')->paginate(7);
        }

        return view('clients', compact('clients'));
    }

    // search client to display
    public function searchClient(Request $request)
    {
        $searchQuery = $request->input('query');

        $clients = Client::where(function ($query) use ($searchQuery) {
            $query->where('phone_number', 'like', '%' . $searchQuery . '%')
                ->orWhere('first_name', 'like', '%' . $searchQuery . '%')
                ->orWhere('last_name', 'like', '%' . $searchQuery . '%');
        })->get();

        return response()->json([
            'success' => true,
            'clients' => $clients->toArray(),
        ]);
    }


    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'phone_number' => ['required', 'regex:/^8\d{10}$/', (new Unique('clients'))->withoutTrashed()->ignoreModel($client)],
            'first_name' => 'required',
            'last_name' => 'nullable',
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
