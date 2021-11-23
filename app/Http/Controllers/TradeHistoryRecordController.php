<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Purchase;
use App\Models\TradeHistoryRecord;
use Illuminate\Http\Request;

class TradeHistoryRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tradeHistoryRecords = TradeHistoryRecord::query()->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('history', ['tradeHistoryRecords' => $tradeHistoryRecords]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request, Company $company, float $currentPrice)
    {
        var_dump($company->getName());
        $totalPrice = $request->get('stocksAmount') * $currentPrice;
        $tradeHistoryRecord = new TradeHistoryRecord([
            'company' => $company->getName(),
            'buy_sell' => 'buy',
            'amount' => $request->get('stocksAmount'),
            'stock_purchase_price' => $currentPrice,
            'total_purchase_price' => $totalPrice
        ]);
        $tradeHistoryRecord->user()->associate(auth()->user());

        $tradeHistoryRecord->save();
    }

    public function sell(Request $request, float $actualPrice, Purchase $purchase)
    {
        $amountToSell = intval($request->get('amountToSell'));
        $tradeHistoryRecord = new TradeHistoryRecord([
            'company' => $purchase->company,
            'buy_sell' => 'sell',
            'amount' => $amountToSell,
            'stock_purchase_price' => $purchase->stock_price,
            'stock_selling_price' => $actualPrice,
            'total_selling_price' => $amountToSell * $actualPrice
        ]);
        $tradeHistoryRecord->user()->associate(auth()->user());

        $tradeHistoryRecord->save();
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\TradeHistoryRecord $tradeHistoryRecord
     * @return \Illuminate\Http\Response
     */
    public function show(TradeHistoryRecord $tradeHistoryRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\TradeHistoryRecord $tradeHistoryRecord
     * @return \Illuminate\Http\Response
     */
    public function edit(TradeHistoryRecord $tradeHistoryRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\TradeHistoryRecord $tradeHistoryRecord
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TradeHistoryRecord $tradeHistoryRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\TradeHistoryRecord $tradeHistoryRecord
     * @return \Illuminate\Http\Response
     */
    public function destroy(TradeHistoryRecord $tradeHistoryRecord)
    {
        //
    }

}
