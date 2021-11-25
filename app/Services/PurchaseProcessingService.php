<?php

namespace App\Services;

use App\Http\Controllers\WalletController;
use App\Models\Company;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseProcessingService
{
    private WalletController $walletController;

    public function __construct()
    {
        $this->walletController = new WalletController();
    }

    public function process(Request $request, Company $company, $currentPrice)
    {
        $totalPrice = $currentPrice * $request->get('stocksAmount');
        $this->walletController->openWallet($totalPrice * -1);
        $stock = Purchase::query()->where('user_id', auth()->id())->where('company', $company->getName());

        if ($stock->exists()) {

            $stock->update([
                'total_price' => $stock->value('total_price') + $totalPrice,
                'amount' => $stock->value('amount') + $request->get('stocksAmount'),
                'stock_price' => ($stock->value('total_price') + $totalPrice) / ($stock->value('amount') + $request->get('stocksAmount'))
            ]);

        } else {
            $purchase = new Purchase([
                'company' => $company->getName(),
                'company_symbol' => $company->getSymbol(),
                'stock_price' => $currentPrice,
                'amount' => $request->get('stocksAmount'),
                'total_price' => $totalPrice
            ]);
            $purchase->user()->associate(auth()->user());
            $purchase->save();
        }
    }
}
