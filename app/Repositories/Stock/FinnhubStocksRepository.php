<?php

namespace App\Repositories\Stock;

use Illuminate\Support\Facades\Http;

class FinnhubStocksRepository implements StocksRepository
{
private string $apiKey;
public function __construct(string $apiKey)
{
    $this->apiKey = $apiKey;
}

    public function getCompanyBySymbol(string $symbol)
    {
        $symbol = strtoupper($symbol);

        $cacheKey = $symbol;
        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        $company = Http::get(
            'https://finnhub.io/api/v1/stock/profile2?symbol=' . $symbol . '&token=' . $this->apiKey);
        cache()->put($cacheKey, $company->json(), now()->addMinute());


        return $company;    }
}
