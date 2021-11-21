<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{


    /**
     * Display a listing of the resource.$wallet
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $wallet = Wallet::query()->where('user_id', auth()->id());
        return view('wallet', ['wallet' => $wallet->value('balance')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function show(Wallet $wallet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function edit(Wallet $wallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(float $sellPrice)
    {
        $wallet = Wallet::query()->where('user_id', auth()->id());
        $balance = $wallet->value('balance');
        $wallet->update([
            'balance' => $balance + $sellPrice,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet $wallet)
    {
        //
    }
    public function openWallet(float $amount) {
        $balance = Wallet::query()->where('user_id', auth()->id())->value('balance');
        Wallet::query()->where('user_id', auth()->id())->update([
            'balance' => $balance + $amount
        ]);
        //var_dump($balance);
    }
}
