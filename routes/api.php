<?php

use App\Http\Controllers\LedgerController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BalanceController;
use Illuminate\Support\Facades\Route;

Route::prefix('ledgers')->group(function () {
    Route::post('/', [LedgerController::class, 'store']); // Create a new ledger with currency setting
});

Route::prefix('transactions')->group(function () {
    Route::post('/', [TransactionController::class, 'store']); // Record a new transaction
});

Route::get('/balances/{ledgerId}', [BalanceController::class, 'show']); // Retrieve ledger balance
