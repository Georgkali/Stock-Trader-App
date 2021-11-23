<?php

namespace App\Rules;

use App\Models\Wallet;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class CanAfford implements Rule
{
    private Request $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function passes($attribute, $value)
    {
        //var_dump($value, (int)$this->request->get('stocksAmount'));die();
        $sum = (float)$value * (int)$this->request->get('stocksAmount');

        return Wallet::query()->where('user_id', auth()
                ->id())
                ->value('balance') >= $sum;
    }


    public function message()
    {
        return 'Not enough money on your balance.';
    }
}
