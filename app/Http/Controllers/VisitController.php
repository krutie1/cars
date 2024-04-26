<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class VisitController extends Controller
{
    public function index(Request $request)
    {
        $visits = Visit::with(['clientTrashed', 'user', 'paymentsTrashed'])
            ->orderBy('id', 'desc')
            ->whereNull('deleted_at')
            ->paginate(12);

        return view('visits', compact('visits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required',
            'start_time_hour' => 'required',
            'end_time_hour' => 'nullable',
            'comment' => 'required',
            'cost' => 'nullable',
            'payment_id' => 'required',
            'user_id' => 'required'
        ]);

        $startTimeHour = $validated['start_time_hour'];
        $endTimeHour = $validated['end_time_hour'];

        $startTime = Carbon::createFromFormat('H:i', $startTimeHour)->toDateTimeString();
        $endTime = $endTimeHour ? Carbon::createFromFormat('H:i', $endTimeHour)->toDateTimeString() : null;

        $validated['start_time'] = $startTime;
        $validated['end_time'] = $endTime;

        unset($validated['start_time_hour']);
        unset($validated['end_time_hour']);

        $visit = Visit::create($validated);

        return response()->json([
            'success' => true,
            'message' => "Создано новое посещение",
            'visit' => $visit
        ]);
    }

    public function update(Request $request, User $manager)
    {
        $validated = $request->validate([
            'phone_number' => ['required', 'regex:/^8\d{10}$/', (new Unique('users'))->withoutTrashed()->ignoreModel($manager)],
            'name' => 'required',
        ], [
            'unique' => 'Пользователь с таким номером телефона уже существует',
        ]);

        if ($manager->update($validated)) {
            return response()->json([
                'success' => true,
                'message' => 'Данные успешно изменены',
                'client' => $manager
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при редактировании клиента',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Request $request, Visit $visit)
    {
        if ($visit->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Посещение успешно удалено',
                'client' => $visit
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении посещения',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
