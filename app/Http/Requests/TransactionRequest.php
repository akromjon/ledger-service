<?php

namespace App\Http\Requests;

use App\Enum\Currency;
use App\Enum\TransactionType;
use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ledger_id' => 'required|exists:ledgers,id',
            'type' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!TransactionType::tryFrom($value)) {
                        $fail('Invalid Transaction type.');
                    }
                }
            ],
            'amount' => 'required|numeric|min:0.01',

            'currency' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!Currency::tryFrom($value)) {
                        $fail('Invalid currency type.');
                    }
                }
            ],
        ];
    }
}
