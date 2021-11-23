<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Repositories\Stock\StocksRepository;
use App\Services\PurchaseService;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    private StocksRepository $stocksRepository;
    private PurchaseService $purchaseService;

    public function __construct(StocksRepository $stocksRepository)
    {
        $this->stocksRepository = $stocksRepository;
        $this->purchaseService = new PurchaseService($this->stocksRepository);
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
        $this->purchaseService->store($request);
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
        $this->purchaseService->sell($purchase, $request);
        $this->update(floatval($request->get('amountToSell')), $purchase);
        return $this->index();
    }


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
