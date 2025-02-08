<?php

namespace App\Http\Controllers;

use App\Http\Requests\LedgerRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Models\Ledger;
class LedgerController extends Controller
{
    public function store(LedgerRequest $request): JsonResponse
    {
        $ledger = Ledger::create(attributes: [
            'currency' => $request->currency
        ]);

        return response()->json(data: $ledger, status: 201);
    }
}
