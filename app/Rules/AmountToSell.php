<?php

namespace App\Rules;

use App\Models\Purchase;
use Illuminate\Contracts\Validation\Rule;

class AmountToSell implements Rule
{

    private Purchase $purchase;

    public function __construct(Purchase $purchase)
    {

        $this->purchase = $purchase;
    }


    public function passes($attribute, $value)
    {

            return floatval($value) <= $this->purchase->amount;
    }


    public function message()
    {
        return 'You cant sell more stocks that you have.';
    }
}
