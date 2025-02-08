<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    public function store(TransactionRequest $request): JsonResponse
    {
        $transaction = Transaction::create(attributes: $request->validated());

        return response()->json(data: $transaction, status: 201);
    }
}
