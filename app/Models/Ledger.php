<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Ledger extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }



    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function transactionsByCurrency():Collection
    {
        return $this->transactions()
            ->select('currency', DB::raw('SUM(CASE WHEN type = "credit" THEN amount ELSE -amount END) as balance'))
            ->groupBy('currency')
            ->get();
    }


}
