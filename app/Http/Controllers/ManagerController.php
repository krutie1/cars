<?php

namespace App\Http\Controllers;

use App\Enums\UserRolesEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rules\Unique;

class ManagerController extends Controller
{
    public function index(Request $request)
    {
        $managers = User::query()->orderBy('id', 'desc')->where('roles', 'like', '%' . UserRolesEnum::MANAGER->value . '%')->paginate(12);
        /** @var LengthAwarePaginator $managers */
        $managers->links();
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
            'message' => 'Менеджер успешно создан',
            'client' => $user
        ]);
    }

    public function update(Request $request, User $manager)
    {
        $validated = $request->validate([
            'phone_number' => ['required', 'regex:/^8\d{10}$/', (new Unique('users'))->withoutTrashed()->ignoreModel($manager)],
            'name' => 'required'
        ]);

        if ($manager->update($validated)) {
            return response()->json([
                'success' => true,
                'message' => 'Менеджер успешно изменён',
                'manager' => $manager
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при изменении менеджера'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function findByPhoneNumber(Request $request)
    {
        $phone_number = $request->input('phone_number');

        if (empty($phone_number)) {
            $managers = User::orderBy('id', 'desc')->paginate(12);
        } else {
            $managers = User::orderBy('id', 'desc')->where('phone_number', $phone_number)->paginate(12);
        }

        return view('managers', compact('managers'));
    }


    public function destroy(Request $request, User $manager)
    {
        if ($manager->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Менеджер успешно удалён',
                'client' => $manager
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении менеджера',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
