<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Purchase;
use App\Repositories\Stock\StocksRepository;
use App\Rules\AmountToSell;
use App\Rules\CanAfford;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    private StocksRepository $stocksRepository;
    private WalletController $walletController;

    public function __construct(StocksRepository $stocksRepository)
    {
        $this->stocksRepository = $stocksRepository;
        $this->walletController = new WalletController();
    }

    public function index()
    {

        $purchases = Purchase::query()->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('portfolio', ['purchases' => $purchases, 'info' => $this->stocksRepository]);
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $company = $this->stocksRepository->getCompanyBySymbol($request->get('symbol'));
        $actualInfo = $this->stocksRepository->getQuote($company);

        $currentPrice = $actualInfo->getCurrent();

        $request->validate([
            'stocksAmount' => ['required', 'numeric', 'gt:0', new CanAfford($currentPrice)],
        ]);

        $totalPrice = $currentPrice * $request->get('stocksAmount');
        $purchase = new Purchase([
            'company' => $company->getName(),
            'company_symbol' => $company->getSymbol(),
            'stock_price' => $currentPrice,
            'amount' => $request->get('stocksAmount'),
            'total_price' => $totalPrice
        ]);

        $purchase->user()->associate(auth()->user());

        $purchase->save();
        $this->walletController->openWallet($totalPrice * -1);
        (new TradeHistoryRecordController)->store($request, $company, $currentPrice);
        return $this->index();
    }

    public function show(Purchase $purchase)
    {
        $company = $this->stocksRepository->getCompanyBySymbol($purchase->company_symbol);
        $actualInfo = $this->stocksRepository->getQuote($company);
        return view('sell', ['purchase' => $purchase, 'actualPrice' => $actualInfo->getCurrent()]);
    }

    public function edit(Purchase $purchase, Request $request) //sell
    {
        $request->validate([
            'amountToSell' => ['required', 'numeric', 'gt:0', new AmountToSell($purchase)]

        ]);
        $company = $this->stocksRepository->getCompanyBySymbol($purchase->company_symbol);
        $actualPrice = $this->stocksRepository->getQuote($company)->getCurrent();
        $sellPrice = intval($request->get('amountToSell')) * $actualPrice;
        $this->walletController->update($sellPrice);
        $this->update(floatval($request->get('amountToSell')), $purchase);
        (new TradeHistoryRecordController)->sell($request, $actualPrice, $purchase);
        return $this->index();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdatePurchaseRequest $request
     * @param \App\Models\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(float $amountToSell, Purchase $purchase)
    {
        $purchase->update([
            'amount' => $purchase->amount - $amountToSell
        ]);
    }


    public function destroy(Purchase $purchase)
    {
        //
    }
}
