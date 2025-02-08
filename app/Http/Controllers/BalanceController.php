<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use Illuminate\Http\JsonResponse;

class BalanceController extends Controller
{
    public function show(string $ledgeruuId): JsonResponse
    {
        $ledger = Ledger::with('transactions')->where('uuid',$ledgeruuId)->first();

        if (null===$ledger) {
            return response()->json(['error' => 'Ledger not found'], 404);
        }

        $balances = $ledger->transactionsByCurrency();

        return response()->json($balances);
    }
}
