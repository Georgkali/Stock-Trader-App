<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradeHistoryRecord extends Model
{
    use HasFactory;

    protected $fillable = ['company',
        'stock_price',
        'buy_sell',
        'amount',
        'stock_purchase_price',
        'stock_selling_price',
        'total_purchase_price',
        'total_selling_price',
        ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
