<?php

namespace App\Rules;

use App\Models\Wallet;
use Illuminate\Contracts\Validation\Rule;

class CanAfford implements Rule
{
    private float $actualPrice;

    public function __construct(float $actualPrice)
    {
        $this->actualPrice = $actualPrice;
    }

    public function passes($attribute, $value)
    {
        $sum = (float)$value * $this->actualPrice;
        return Wallet::query()->where('user_id', auth()
                ->id())
                ->value('balance') >= $sum;
    }


    public function message()
    {
        return 'Not enough money on your balance.';
    }
}
