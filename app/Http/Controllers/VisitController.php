<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class VisitController extends Controller
{
    public function index(Request $request)
    {
        $visits = Visit::with(['clientTrashed', 'userTrashed', 'paymentsTrashed'])
            ->orderBy('id', 'desc')
            ->paginate(12);

        $payments = Payment::pluck('name', 'id');

        $this->calculateTotalPayments($visits);

        return view('visits', compact('visits', 'payments'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'client_id' => 'required',
            'start_time_hour' => 'required',
            'end_time_hour' => 'nullable',
            'comment' => 'required',
//            'user_id' => 'required',
            'payment_types' => 'required|array',
            'payment_types.*' => 'required|exists:payments,id',
            'payment_amounts' => 'required|array',
            'payment_amounts.*' => 'required|numeric|min:0',
        ]);

        $validatedData['user_id'] = auth()->user()->id;

        $visit = $this->createVisit($validatedData);

        return response()->json([
            'success' => true,
            'message' => "Создано новое посещение",
            'visit' => $visit
        ]);
    }

    public function findByPhoneNumber(Request $request)
    {
        $phoneNumber = $request->input('phone_number');

        $visits = Visit::query()
            ->whereHas('clientTrashed', function ($query) use ($phoneNumber) {
                $query->when($phoneNumber, function ($q) use ($phoneNumber) {
                    $q->where('phone_number', 'like', '%' . $phoneNumber . '%');
                });
            })
            ->with(['clientTrashed', 'userTrashed', 'paymentsTrashed'])
            ->orderBy('id', 'desc')
            ->paginate(12);

        $payments = Payment::pluck('name', 'id');

        $this->calculateTotalPayments($visits);

        return view('visits', compact('visits', 'payments'));
    }

    public function update(Request $request, User $manager)
    {
        // Your update logic here
    }

    public function destroy(Request $request, Visit $visit)
    {
        if ($visit->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Посещение успешно удалено',
                'visit' => $visit
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении посещения',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function calculateTotalPayments($visits)
    {
        foreach ($visits as $visit) {
            $totalPayments = [];
            $totalSum = 0;

            $transactions = $visit->transactions()->with('paymentsTrashed')->get();

            foreach ($transactions as $transaction) {
                $paymentType = $transaction->paymentsTrashed->name;
                $amount = $transaction->amount;

                $totalSum += $amount;

                if (!isset($totalPayments[$paymentType])) {
                    $totalPayments[$paymentType] = $amount;
                } else {
                    $totalPayments[$paymentType] += $amount;
                }
            }

            $formattedPayments = [];
            foreach ($totalPayments as $type => $amount) {
                $formattedPayments[] = "{$type}: {$amount}";
            }

            $formattedPayments[] = "Сумма: $totalSum";

            $visit->totalPayments = implode('<br>', $formattedPayments);
        }
    }

    private function createVisit($validatedData)
    {
        $startTimeHour = $validatedData['start_time_hour'];
        $endTimeHour = $validatedData['end_time_hour'];

        $startTime = Carbon::createFromFormat('H:i', $startTimeHour)->toDateTimeString();
        $endTime = $endTimeHour ? Carbon::createFromFormat('H:i', $endTimeHour)->toDateTimeString() : null;

        $validatedData['start_time'] = $startTime;
        $validatedData['end_time'] = $endTime;

        unset($validatedData['start_time_hour']);
        unset($validatedData['end_time_hour']);

        $visit = Visit::create($validatedData);

        $paymentTypes = $validatedData['payment_types'];
        $paymentAmounts = $validatedData['payment_amounts'];

        foreach ($paymentTypes as $key => $paymentType) {
            $transaction = new Transaction([
                'visit_id' => $visit->id,
                'payment_id' => $paymentType,
                'amount' => $paymentAmounts[$key],
            ]);
            $transaction->save();
        }

        return $visit;
    }
}
