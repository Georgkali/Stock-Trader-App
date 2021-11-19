<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Quote;
use App\Repositories\Stock\StocksRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class StocksController extends Controller
{
    private StocksRepository $stocksRepository;

    public function __construct(StocksRepository $stocksRepository)
    {
        $this->stocksRepository = $stocksRepository;
    }

    public function search(Request $request)
    {
        $companyName = strtolower($request->get('query'));
        $cacheKey = Str::snake($companyName);
        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        $companies = Http::get(
            'https://finnhub.io/api/v1/search?q=' . $companyName . '&token=' . env('FINNHUB_API_KEY'));

        cache()->put($cacheKey, $companies->json('result')[0], now()->addMinute());

        return $companies->json('result')[0];

    }

    public function view(Request $request)
    {

        $symbol = $request->get('symbol');
        $company = $this->stocksRepository->getCompanyBySymbol($symbol);
        $quote = $this->stocksRepository->getQuote($company);
        return view('dashboard',['company' => $company, 'quote' => $quote]);

    }
}
