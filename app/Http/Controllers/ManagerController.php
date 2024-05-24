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
        $managers = User::query()->orderBy('id', 'desc')->where('roles', 'like', '%' . UserRolesEnum::MANAGER->value . '%')->paginate(7);
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

        if (User::withTrashed()->where('phone_number', $validated['phone_number'])->exists()) {
            $user = User::restore($validated);
        } else {
            $user = User::create($validated);
        }

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
            'name' => 'required',
            'password' => 'required'
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

    public function find(Request $request)
    {
        $searchQuery = $request->input('search_query');

        if (empty($searchQuery)) {
            $managers = User::orderBy('id', 'desc')->paginate(7);
        } else {
            $managers = User::orderBy('id', 'desc')->where(function ($query) use ($searchQuery) {
                $query->where('phone_number', 'like', '%' . $searchQuery . '%')
                    ->orWhere('name', 'like', '%' . $searchQuery . '%');
            })->paginate(7);
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
