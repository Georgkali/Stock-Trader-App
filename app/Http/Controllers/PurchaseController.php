<?php

namespace App\Http\Controllers;

use App\Events\SendPurchaseEmail;
use App\Events\SendSellingEmail;
use App\Models\Purchase;
use App\Repositories\Stock\StocksRepository;
use App\Services\BusinessHoursService;
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
        if ((new BusinessHoursService())->now()) {
            return view('portfolio', ['purchases' => $purchases, 'info' => $this->stocksRepository]);
        }
        return view('portfolio', ['purchases' => $purchases, 'info' => $this->stocksRepository, 'holiday' => true]);

    }


    public function store(Request $request)
    {

        $this->purchaseService->store($request);
        event(new SendPurchaseEmail($request));
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
        event(new SendSellingEmail($purchase, $request));
        return $this->index();
    }


    public function update(float $amountToSell, Purchase $purchase)
    {
        $purchase->update([
            'amount' => $purchase->amount - $amountToSell
        ]);
    }

}
