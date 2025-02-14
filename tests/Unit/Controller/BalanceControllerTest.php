<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Ledger;
use App\Models\Transaction;
use App\Enum\Currency;
use App\Enum\TransactionType;
use Illuminate\Support\Str;

class BalanceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_retrieve_correct_balances_by_currency():void
    {
        $ledger = Ledger::create(['uuid' => Str::uuid(), 'currency' => Currency::USD->value]);

        Transaction::create([ 'ledger_id' => $ledger->id, 'type' => TransactionType::CREDIT->value, 'amount' => 200, 'currency' => Currency::USD->value]);
        Transaction::create([ 'ledger_id' => $ledger->id, 'type' => TransactionType::DEBIT->value, 'amount' => 50, 'currency' => Currency::USD->value]);
        Transaction::create([ 'ledger_id' => $ledger->id, 'type' => TransactionType::CREDIT->value, 'amount' => 100, 'currency' => Currency::BTC->value]);
        Transaction::create([ 'ledger_id' => $ledger->id, 'type' => TransactionType::DEBIT->value, 'amount' => 30, 'currency' => Currency::BTC->value]);

        $response = $this->getJson("/api/balances/{$ledger->uuid}");

        $response->assertStatus(200)
                 ->assertJson([
                     ['currency' => Currency::USD->value, 'balance' => 150],
                     ['currency' => Currency::BTC->value, 'balance' => 70]
                 ]);
    }

    public function test_returns_404_if_ledger_not_found():void
    {
        $response = $this->getJson("/api/balances/" . Str::uuid());

        $response->assertStatus(404)
                 ->assertJson(['error' => 'Ledger not found']);
    }
}
