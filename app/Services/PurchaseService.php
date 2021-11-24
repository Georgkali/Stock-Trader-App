<?php

namespace App\Services;

use App\Http\Controllers\TradeHistoryRecordController;
use App\Http\Controllers\WalletController;
use App\Models\Purchase;
use App\Repositories\Stock\StocksRepository;
use App\Rules\AmountToSell;
use App\Rules\CanAfford;
use Illuminate\Http\Request;

class PurchaseService
{

    private StocksRepository $stocksRepository;
    private WalletController $walletController;
    private TradeHistoryRecordController $tradeHistoryRecordController;

    public function __construct(StocksRepository $stocksRepository)
    {
        $this->stocksRepository = $stocksRepository;
        $this->walletController = new WalletController();
        $this->tradeHistoryRecordController = new TradeHistoryRecordController();
    }

    public function store(Request $request)
    {
        $company = $this->stocksRepository->getCompanyBySymbol($request->get('symbol'));
        $actualInfo = $this->stocksRepository->getQuote($company);
        $currentPrice = $actualInfo->getCurrent();

        $request->validate([
            'stocksAmount' => ['required', 'numeric', 'gt:0', new CanAfford($currentPrice)],
        ]);
        (new PurchaseProcessingService())->process($request, $company, $currentPrice);
        $this->tradeHistoryRecordController->store($request, $company, $currentPrice);
    }

    public function sell(Purchase $purchase, Request $request)
    {
        $request->validate([
            'amountToSell' => ['required', 'numeric', 'gt:0', new AmountToSell($purchase)]

        ]);
        $company = $this->stocksRepository->getCompanyBySymbol($purchase->company_symbol);
        $actualPrice = $this->stocksRepository->getQuote($company)->getCurrent();
        $sellPrice = intval($request->get('amountToSell')) * $actualPrice;
        $this->walletController->update($sellPrice);
        $this->tradeHistoryRecordController->sell($request, $actualPrice, $purchase);
    }

}
