<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Payment;
use App\Models\Price;
use App\Models\Transaction;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class VisitController extends Controller
{
    public function index(Request $request)
    {
        $with = ['transactions', 'transactions.paymentsTrashed', 'clientTrashed', 'userTrashed', 'paymentsTrashed'];

        if (auth()->user()->isAdmin()) {
            $visits = Visit::with($with)
                ->orderBy('id', 'desc')
                ->paginate(12);
        } else {
            $visits = Visit::with($with)
                ->whereDate('created_at', now())
                ->orderBy('id', 'desc')
                ->paginate(12);
        }

        $payments = Payment::query()->pluck('name', 'id');

        $this->calculateTotalPayments($visits);

        return view('visits', compact('visits', 'payments'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'client_id' => 'required',
            'start_time' => 'required',
            'comment' => 'required'
        ]);

        $validatedData['user_id'] = auth()->user()->id;

        $client = Client::find($validatedData['client_id']);

        if ($client) {
            $unpaidVisits = $client->visits()->whereNull('payment_date')->exists();

            if ($unpaidVisits) {
                return response()->json([
                    'success' => false,
                    'message' => 'У вас есть неоплаченное посещение, сделайте рассчет или удалите существующее посещение.',
                ]);
            }
        }

//        $visit = $this->createVisit($validatedData);
        $visit = Visit::create($validatedData);

        $client->visits_count += 1;
        $client->save();

        $discount = 0;

        if ($client->visits_count % 10 === 0) {
            $discount = 100;
        } else if ($client->visits_count % 5 === 0) {
            $discount = 50;
        }

        $visit->discount = $discount;
        $visit->save();

        return response()->json([
            'success' => true,
            'message' => "Создано новое посещение",
            'visit' => $visit
        ]);
    }

    public function setPayment(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'payment_types' => 'required|array',
            'payment_types.*' => 'required|exists:payments,id',
            'payment_amounts' => 'required|array',
            'payment_amounts.*' => 'required|numeric|min:0'
        ]);

        $paymentTypes = $validated['payment_types'];
        $paymentAmounts = $validated['payment_amounts'];

        unset($validated['payment_types']);
        unset($validated['payment_amounts']);

        foreach ($paymentTypes as $key => $paymentType) {
            $transaction = new Transaction([
                'visit_id' => $visit->id,
                'payment_id' => $paymentType,
                'amount' => $paymentAmounts[$key],
            ]);
            $transaction->save();
        }

        $validated['payment_date'] = now();

        if ($visit->update($validated)) {
            return response()->json([
                'success' => true,
                'message' => 'Поле оплаты обновлено'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка обновления оплаты '
            ], 404);
        }
    }

    public function filter(Request $request)
    {
        $startDate = $request->input('start');
        $endDate = $request->input('end');

        $with = ['transactions', 'transactions.paymentsTrashed', 'clientTrashed', 'userTrashed', 'paymentsTrashed'];

        if (!$endDate) {
            $endDate = now();
        }

        if ($startDate) {
            $visitsQuery = Visit::with($with)
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->orderBy('id', 'desc');

            $visits = $visitsQuery->paginate(12);
        } else {
            if (auth()->user()->isAdmin()) {
                $visits = Visit::with($with)
                    ->orderBy('id', 'desc')
                    ->paginate(12);
            } else {
                $visits = Visit::with($with)
                    ->whereDate('created_at', now())
                    ->orderBy('id', 'desc')
                    ->paginate(12);
            }
        }

        $payments = Payment::pluck('name', 'id');

        $this->calculateTotalPayments($visits);

        return view('visits', compact('visits', 'payments'));
    }

    public function findByPhoneNumber(Request $request)
    {
        $with = ['transactions', 'transactions.paymentsTrashed', 'clientTrashed', 'userTrashed', 'paymentsTrashed'];

        $phoneNumber = $request->input('phone_number');

        $visits = Visit::query()
            ->whereHas('clientTrashed', function ($query) use ($phoneNumber) {
                $query->when($phoneNumber, function ($q) use ($phoneNumber) {
                    $q->where('phone_number', 'like', '%' . $phoneNumber . '%');
                });
            })
            ->with($with)
            ->orderBy('id', 'desc')
            ->paginate(12);

        $payments = Payment::pluck('name', 'id');

        $this->calculateTotalPayments($visits);

        return view('visits', compact('visits', 'payments'));
    }

    public function update(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'end_time' => 'required'
        ]);

        $start = $visit->start_time;
        $end = Carbon::parse($validated['end_time']);

        // Check if end time is before start time
        if ($end < $start) {
            return response()->json([
                'success' => false,
                'message' => 'Время окончания не может быть раньше времени начала'
            ]);
        }

        $visit->end_time = $end;
        $visit->save();

        $priceTable = Price::pluck('cost', 'minute')->toArray();
        if (!$priceTable) {
            return response()->json([
                'success' => false,
                'message' => 'Отсутвуют данные по тарифу, загрузите Excel'
            ]);
        }

        $totalMinutes = $end->diffInMinutes($start);

        $totalPrice = 0;

        if ($totalMinutes < 60) {
            // If total duration is less than 60 minutes, set price as 1100
            $totalPrice = $priceTable[60]; // Assuming 1100 is the price for 60 minutes
        } else if (isset($priceTable[$totalMinutes])) {
            // If the exact duration exists in the price table
            $totalPrice = $priceTable[$totalMinutes];
        } else {
            // Calculate based on hours (if not found in the table)
            $totalHours = floor($totalMinutes / 60); // Complete hours
            $remainingMinutes = $totalMinutes - ($totalHours * 60); // Remaining minutes

            $hoursPrice = $priceTable[60] * $totalHours; // Price for complete hours

            $remainingMinutesPrice = 0;
            if ($remainingMinutes > 0) {
                $remainingMinutesPrice = $priceTable[$remainingMinutes]; // Price for remaining minutes
            }

            $totalPrice = $hoursPrice + $remainingMinutesPrice;
        }

        $discountedPrice = $totalPrice;
        $client = $visit->client;

        if ($client->visits_count % 10 === 0) {
            $discountedPrice = 0;
        } else if ($client->visits_count % 5 === 0) {
            $discountedPrice = $totalPrice * 0.5;
        }

//        $discountedPrice = ceil($discountedPrice / 10) * 10;

        $visit->cost = $discountedPrice;
        $visit->save();

        Log::error("total price: $totalPrice");
        Log::error("discounted price: $discountedPrice");

        return response()->json([
            'success' => true,
            'message' => 'Время завершения обновлено',
            'totalPrice' => $totalPrice
        ]);

    }


    public function destroy(Request $request, Visit $visit)
    {
        $client = $visit->client;

        if ($visit->delete()) {
            $client->visits_count -= 1;
            $client->save();

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

            foreach ($visit->transactions as $transaction) {
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

            if ($totalSum == 0) {
                $visit->totalPayments = "--";
            } else {
                $formattedPayments[] = "Сумма: $totalSum";
                $visit->totalPayments = implode('<br>', $formattedPayments);
            }
        }
    }

    private function setPaymentDate(Request $request, Visit $visit)
    {
        $visit->payment_date = now();
        $visit->save();

        $visit->update(['payment_date' => now()]);
    }

    private function calculateDifference()
    {

    }

    private function createVisit($validatedData)
    {
        $startTimeHour = $validatedData['start_time_hour'];
//        $endTimeHour = $validatedData['end_time_hour'];

        $startTime = Carbon::createFromFormat('H:i', $startTimeHour)->toDateTimeString();
//        $endTime = $endTimeHour ? Carbon::createFromFormat('H:i', $endTimeHour)->toDateTimeString() : null;

        $validatedData['start_time'] = $startTime;

        unset($validatedData['start_time_hour']);

        $paymentTypes = $validatedData['payment_types'];
        $paymentAmounts = $validatedData['payment_amounts'];

        unset($validatedData['payment_types']);
        unset($validatedData['payment_amounts']);

        $visit = Visit::create($validatedData);

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
