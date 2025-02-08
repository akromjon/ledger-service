<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Ledger;
use App\Enum\Currency;
use App\Enum\TransactionType;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    public static function validTransactions(): array
    {
        return [
            'valid debit transaction' => [TransactionType::DEBIT->value, 100.50, Currency::USD->value],
            'valid credit transaction' => [TransactionType::CREDIT->value, 200.75, Currency::BTC->value],
        ];
    }

    public static function invalidTransactions(): array
    {
        return [
            'invalid type' => ['invalid_type', 100, Currency::USD->value],
            'negative amount' => [TransactionType::DEBIT->value, -50, Currency::USD->value],
            'zero amount' => [TransactionType::CREDIT->value, 0, Currency::USD->value],
            'invalid currency' => [TransactionType::DEBIT->value, 100, 'INVALID'],
        ];
    }

    #[DataProvider('validTransactions')]
    public function test_can_create_transaction_with_valid_data($type, $amount, $currency)
    {
        $ledger = Ledger::create([
            'currency' => Currency::USD->value
        ]);


        $response = $this->postJson('/api/transactions', [
            'ledger_id' => $ledger->id,
            'type' => $type,
            'amount' => $amount,
            'currency' => $currency,
            'uuid' => Str::uuid(),
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'uuid',
                'ledger_id',
                'type',
                'amount',
                'currency',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('transactions', [
            'ledger_id' => $ledger->id,
            'type' => $type,
            'amount' => $amount,
            'currency' => $currency,
        ]);
    }

    #[DataProvider('invalidTransactions')]
    public function test_cannot_create_transaction_with_invalid_data($type, $amount, $currency)
    {
        $ledger = Ledger::create(['currency' => Currency::USD->value]);

        $response = $this->postJson('/api/transactions', [
            'ledger_id' => $ledger->id,
            'type' => $type,
            'amount' => $amount,
            'currency' => $currency,
        ]);

        $response->assertStatus(422);
    }
}
