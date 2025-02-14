<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Ledger;
use App\Models\Transaction;
use App\Enum\Currency;
use App\Enum\TransactionType;


class LedgerBalanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_retrieve_correct_balances_by_currency(): void
    {
        $ledger = Ledger::create(['currency' => Currency::USD->value]);


        Transaction::create([ 'ledger_id' => $ledger->id, 'type' => TransactionType::CREDIT->value, 'amount' => 200, 'currency' => Currency::USD->value]);
        Transaction::create([ 'ledger_id' => $ledger->id, 'type' => TransactionType::DEBIT->value, 'amount' => 50, 'currency' => Currency::USD->value]);
        Transaction::create([ 'ledger_id' => $ledger->id, 'type' => TransactionType::CREDIT->value, 'amount' => 100, 'currency' => Currency::BTC->value]);
        Transaction::create([ 'ledger_id' => $ledger->id, 'type' => TransactionType::DEBIT->value, 'amount' => 30, 'currency' => Currency::BTC->value]);

        $balances = $ledger->transactionsByCurrency();

        $this->assertCount(2, $balances);
        $this->assertEquals(150, $balances->where('currency', Currency::USD->value)->first()->balance);
        $this->assertEquals(70, $balances->where('currency', Currency::BTC->value)->first()->balance);
    }
}
