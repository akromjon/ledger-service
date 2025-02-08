<?php

namespace App\Http\Requests;

use App\Enum\Currency;
use Illuminate\Foundation\Http\FormRequest;

class LedgerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'currency' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!Currency::tryFrom($value)) {
                    $fail('Invalid currency type.');
                }
            }]
        ];
    }
}
