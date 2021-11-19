<?php

namespace App\Repositories\Stock;

use App\Models\Company;
use App\Models\Quote;
use Illuminate\Support\Facades\Http;

class FinnhubStocksRepository implements StocksRepository
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getCompanyBySymbol(string $symbol): Company
    {
        $symbol = strtoupper($symbol);

        $cacheKey = $symbol;
        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        $companyName = Http::get(
            'https://finnhub.io/api/v1/stock/profile2?symbol=' . $symbol . '&token=' . $this->apiKey);

        $company = new Company(

            $companyName['name'],
            $symbol,
            $companyName['logo']
        );

        cache()->put($cacheKey, $company, now()->addMinute());


        return $company;
    }

    public function getQuote(Company $company): Quote
    {
        $ResponseData = Http::get('https://finnhub.io/api/v1/quote?symbol=' . $company
                ->getSymbol() . '&token=' . $this->apiKey)->json();

        // https://finnhub.io/api/v1/quote?symbol=AAPL&token=c69rnfiad3idi8g5i8gg
        return new Quote(
            $ResponseData['o'],
            $ResponseData['pc'],
            $ResponseData['c']
        );

    }
}
