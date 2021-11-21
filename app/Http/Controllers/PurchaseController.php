<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Purchase;
use App\Repositories\Stock\StocksRepository;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    private StocksRepository $stocksRepository;

    public function __construct(StocksRepository $stocksRepository)
    {
        $this->stocksRepository = $stocksRepository;
    }

    public function index()
    {
        $purchases = Purchase::query()->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('portfolio', ['purchases' => $purchases]);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StorePurchaseRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'companySymbol' => 'required',
            'currentPrice' => 'required',
            'stocksAmount' => 'required|gt:0'

        ]);
        $totalPrice = $request->get('currentPrice') * $request->get('stocksAmount');
        $purchase = new Purchase([
            'company' => $request->get('companyName'),
            'company_symbol' => $request->get('companySymbol'),
            'stock_price' => $request->get('currentPrice'),
            'amount' => $request->get('stocksAmount'),
            'total_price' => $totalPrice
        ]);

        $purchase->user()->associate(auth()->user());

        $purchase->save();
        (new WalletController())->openWallet($totalPrice * -1);
        return $this->index();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        $company = $this->stocksRepository->getCompanyBySymbol($purchase->company_symbol);
        $actualInfo = $this->stocksRepository->getQuote($company);
        return view('sell', ['purchase' => $purchase, 'actualPrice' => $actualInfo->getCurrent()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdatePurchaseRequest $request
     * @param \App\Models\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
