<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //
    public function insert(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric'
        ]);

        $validated['payment_id'] = 1;

        Transaction::create($validated);

        return response()->json([
            'success' => true,
            'message' => "Платёж внесён"
        ]);
    }
}
