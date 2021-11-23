<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{



    public function index()
    {

        $wallet = Wallet::query()->where('user_id', auth()->id());
        return view('wallet', ['wallet' => $wallet->value('balance')]);
    }


    public function create()
    {

    }


    public function store()
    {
        $wallet = new Wallet([
            'balance' => 10000
        ]);
        $wallet->user()->associate(auth()->user());
        $wallet->save();
     }


    public function show(Wallet $wallet)
    {
        //
    }


    public function edit(Wallet $wallet)
    {
        //
    }


    public function update(float $sellPrice)
    {
        $wallet = Wallet::query()->where('user_id', auth()->id());
        $balance = $wallet->value('balance');
        $wallet->update([
            'balance' => $balance + $sellPrice,
        ]);

    }


    public function destroy(Wallet $wallet)
    {
        //
    }
    public function openWallet(float $amount) {
        $balance = Wallet::query()->where('user_id', auth()->id())->value('balance');
        Wallet::query()->where('user_id', auth()->id())->update([
            'balance' => $balance + $amount
        ]);
    }
}
