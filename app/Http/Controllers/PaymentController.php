<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::query()->withTrashed()->orderBy('id', 'desc')->paginate(12);
        return view('payments', compact('payments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:payments'
        ], [
            'unique' => 'Платёж с таким названием уже существует',
        ]);

        $payment = Payment::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Платёж успешно добавлен',
            'payment' => $payment
        ]);
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        if ($payment->update($validated)) {
            return response()->json([
                'success' => true,
                'message' => 'Платёж успешно изменён',
                'payment' => $payment
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при изменении платежа'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restore($id)
    {
        $payment = Payment::onlyTrashed()->findOrFail($id);

        if ($payment->restore()) {
            return response()->json([
                'success' => true,
                'message' => 'Платёж успешно добавлен',
                'payment' => $payment,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при сохранении платежа',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function destroy(Request $request, Payment $payment)
    {
        if ($payment->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Платёж успешно удалён',
                'client' => $payment
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении платежа',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
