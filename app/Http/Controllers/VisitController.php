<?php

namespace App\Http\Controllers;

use App\Models\Car;
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
        $with = ['transactions', 'transactions.paymentsTrashed', 'clientTrashed', 'userTrashed', 'carTrashed', 'paymentsTrashed'];

        $startDate = $request->input('start');
        $endDate = $request->input('end');

        if (auth()->user()->isAdmin()) {
            $visits = Visit::with($with)
                ->orderBy('id', 'desc')
                ->paginate(7);
        } else {
            $visits = Visit::with($with)
                ->whereDate('created_at', now())
                ->orderBy('id', 'desc')
                ->paginate(7);
        }

        $payments = Payment::query()->pluck('name', 'id');

        $this->calculateTotalPayments($visits);

        $total = 0;
        $totalByType = [];

        foreach ($visits as $visit) {
            foreach ($visit->transactions as $transaction) {
                $type = $transaction->paymentsTrashed->name;
                $amount = $transaction->amount;

                if (isset($totalByType[$type])) {
                    $totalByType[$type] += $amount;
                } else {
                    $totalByType[$type] = $amount;
                }

                $total += $amount;
            }
        }

        return view('visits', compact('visits', 'payments', 'startDate', 'endDate', 'total', 'totalByType'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'client_id' => 'required',
            'start_time' => 'required',
            'comment' => 'required',
            'car_id' => 'required'
        ]);

        $validatedData['user_id'] = auth()->user()->id;

        $client = Client::find($validatedData['client_id']);

        $visit = Visit::create($validatedData);

        $client->increment('visits_count');

        $discount = 0;
        $visitsCount = $client->visits_count;

        if ($visitsCount % 10 === 0) {
            $discount = 100;
        } else if ($visitsCount % 5 === 0) {
            $discount = 50;
        }

        $carId = $validatedData['car_id'];

        $car = Car::find($carId);
        if ($car) {
            $car->active = true;
            $car->save();
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

        $existingPayments = $visit->transactions()->pluck('payment_id')->toArray();

        foreach ($paymentTypes as $key => $paymentType) {
            $amount = $paymentAmounts[$key];

            if (in_array($paymentType, $existingPayments)) {
                // Update existing transaction's amount
                $transaction = $visit->transactions()->where('payment_id', $paymentType)->first();
                $transaction->amount = $amount;
                $transaction->save();
            } else {
                // Create new transaction
                $transaction = new Transaction([
                    'visit_id' => $visit->id,
                    'payment_id' => $paymentType,
                    'amount' => $amount,
                ]);
                $transaction->save();
            }
        }

        $validated['payment_date'] = now();


        if ($visit->update($validated)) {
            // removing active from cars
            if ($visit->car) {
                $visit->car->active = false;
                $visit->car->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Данные об оплате внесены'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при внесении оплаты'
            ], 404);
        }
    }


    public function filter(Request $request)
    {
        $startDate = $request->input('start');
        $endDate = $request->input('end');

        $total = 0;

        $with = ['transactions', 'transactions.paymentsTrashed', 'clientTrashed', 'userTrashed', 'carTrashed', 'paymentsTrashed'];

        if (!$endDate) {
            $endDate = now();
        }

        if ($startDate) {
            $visitsQuery = Visit::with($with)
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->orderBy('id', 'desc');

            $visits = $visitsQuery->paginate(7);
        } else {
            if (auth()->user()->isAdmin()) {
                $visits = Visit::with($with)
                    ->orderBy('id', 'desc')
                    ->paginate(7);
            } else {
                $visits = Visit::with($with)
                    ->whereDate('created_at', now())
                    ->orderBy('id', 'desc')
                    ->paginate(7);
            }
        }

        $payments = Payment::pluck('name', 'id');

        $this->calculateTotalPayments($visits);

        if (!empty($startDate)) {
            $startDate = Carbon::parse($startDate)->startOfDay();
        }

        if (!empty($endDate)) {
            $endDate = Carbon::parse($endDate)->endOfDay();
        }

        $totalByType = [];

        foreach ($visits as $visit) {
            foreach ($visit->transactions as $transaction) {
                $type = $transaction->paymentsTrashed->name;
                $amount = $transaction->amount;

                if (isset($totalByType[$type])) {
                    $totalByType[$type] += $amount;
                } else {
                    $totalByType[$type] = $amount;
                }

                $total += $amount;
            }
        }

        return view('visits', compact('visits', 'payments', 'startDate', 'endDate', 'total', 'totalByType'));
    }

    public function find(Request $request)
    {
        $with = ['transactions', 'transactions.paymentsTrashed', 'clientTrashed', 'userTrashed', 'carTrashed', 'paymentsTrashed'];

        $searchQuery = $request->input('search_query');

        $query = Visit::query()
            ->whereHas('clientTrashed', function ($query) use ($searchQuery) {
                $query->when($searchQuery, function ($q) use ($searchQuery) {
                    $q->where('phone_number', 'like', '%' . $searchQuery . '%')
                        ->orWhere('first_name', 'like', '%' . $searchQuery . '%')
                        ->orWhere('last_name', 'like', '%' . $searchQuery . '%');
                });
            })
            ->orWhereHas('carTrashed', function ($query) use ($searchQuery) {
                $query->when($searchQuery, function ($q) use ($searchQuery) {
                    $q->where('name', 'like', '%' . $searchQuery . '%');
                });
            })
            ->with($with)
            ->orderBy('id', 'desc');

        if (auth()->user()->isAdmin()) {
            $visits = $query->paginate(7);
        } else {
            $visits = $query->whereDate('created_at', now())->paginate(7);
        }

        $payments = Payment::pluck('name', 'id');

        $this->calculateTotalPayments($visits);

        $total = 0;
        $totalByType = [];

        foreach ($visits as $visit) {
            foreach ($visit->transactions as $transaction) {
                $type = $transaction->paymentsTrashed->name;
                $amount = $transaction->amount;

                if (isset($totalByType[$type])) {
                    $totalByType[$type] += $amount;
                } else {
                    $totalByType[$type] = $amount;
                }

                $total += $amount;
            }
        }

        return view('visits', compact('visits', 'payments', 'total', 'totalByType'));
    }


    public function update(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'end_time' => 'required'
        ]);

        $start = $visit->start_time;
        $end = Carbon::parse($validated['end_time']);

        $end->year($start->year);
        $end->month($start->month);
        $end->day($start->day);

        // Check if end time is before start time
        if ($end < $start) {
            return response()->json([
                'success' => false,
                'message' => 'Время окончания не может быть раньше времени начала'
            ]);
        }

//        $client = $visit->client;
//        if ($client) {
//            // Perform operations related to the client
//            $client->increment('visits_count');
//        } else {
//            // Handle the case where the client is not found
//            return response()->json([
//                'success' => false,
//                'message' => 'Клиент не найден',
//            ]);
//        }

        $priceTable = Price::pluck('cost', 'minute')->toArray();
        if (!$priceTable) {
            return response()->json([
                'success' => false,
                'message' => 'Отсутвуют данные по тарифу, загрузите Excel'
            ]);
        }

        $visit->end_time = $end;
        $visit->save();

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
        $visitDiscount = $visit->discount;

        if ($visitDiscount == 100) {
            $discountedPrice = 0;
        } else if ($visitDiscount == 50) {
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
            if ($visit->car) {
                $visit->car->active = false;
                $visit->car->save();
            }

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
                $visit->displayPayments = "--";
                $visit->totalPayments = 0;
            } else {
                $formattedPayments[] = "Сумма: $totalSum";
                $visit->displayPayments = implode('<br>', $formattedPayments);
                $visit->totalPayments = $totalSum;
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
