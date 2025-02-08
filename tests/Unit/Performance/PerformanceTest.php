<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Ledger;
use App\Models\Transaction;
use App\Enum\Currency;
use App\Enum\TransactionType;
use Illuminate\Support\Str;

class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_handles_1000_transactions_per_minute()
    {
        $ledger = Ledger::create(['uuid' => Str::uuid(), 'currency' => Currency::USD->value]);

        $transactions = [];

        for ($i = 0; $i < 1000; $i++) {
            $transactions[] = [
                'uuid'=>Str::uuid()->toString(),
                'ledger_id' => $ledger->id,
                'type' => TransactionType::CREDIT->value,
                'amount' => rand(1, 1000),
                'currency' => Currency::USD->value,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        $startTime = microtime(true);

        Transaction::insert($transactions);

        $executionTime = microtime(true) - $startTime;

        $this->assertLessThan(60, $executionTime, "The application should process 1,000 transactions within 60 seconds.");
    }
}

