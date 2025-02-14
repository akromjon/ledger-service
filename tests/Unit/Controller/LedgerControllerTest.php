<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use App\Enum\Currency;


class LedgerControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * valid currencies
     */
    public static function validCurrencies(): array
    {
        return [
            'USD currency' => [Currency::USD->value],
            'BTC currency' => [Currency::BTC->value],
        ];
    }

    /**
     * invalid currencies
     */
    public static function invalidCurrencies(): array
    {
        return [
            'invalid currency' => ['INVALID'],
            'empty currency' => [''],
            'null currency' => [null],
        ];
    }

    #[Test]
    #[DataProvider('validCurrencies')]
    public function test_can_create_ledger_with_valid_currency($currency):void
    {
        $response = $this->postJson('/api/ledgers', [
            'currency' => $currency
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'uuid',
                'currency',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('ledgers', ['currency' => $currency]);
    }

    #[Test]
    #[DataProvider('invalidCurrencies')]
    public function test_cannot_create_ledger_with_invalid_currency($currency): void
    {
        $response = $this->postJson('/api/ledgers', [
            'currency' => $currency
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['currency']);
    }
}
