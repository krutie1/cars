<?php

namespace App\Http\Controllers;

use App\Enums\UserRolesEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Unique;

class ManagerController extends Controller
{
    public function index(Request $request)
    {
        $managers = User::orderBy('id', 'desc')->where('roles', 'like', '%' . UserRolesEnum::MANAGER->value . '%')->paginate(12);

        return view('managers', compact('managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => ['required', 'regex:/^8\d{10}$/', (new Unique('users'))->withoutTrashed()],
            'name' => 'required',
            'password' => 'required'
        ], [
            'unique' => 'Пользователь с таким номером телефона уже существует'
        ]);

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Клиент успешно создан',
            'client' => $user
        ]);
    }

}
