<?php

namespace App\Repositories\Stock;

use App\Models\Company;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FinnhubStocksRepository implements StocksRepository
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getCompany(string $name)
    {

        $companyName = strtolower($name);

        $cacheKey = "symb" . Str::snake($companyName);
        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey)['symbol'];
        }

        $company = Http::get(
            'https://finnhub.io/api/v1/search?q=' . $companyName . '&token=' . $this->apiKey)
            ->json('result')[0];

        cache()->put($cacheKey, $company, now()->addMonth());
        return $company['symbol'];
    }

    public function getCompanyBySymbol(string $symbol): Company
    {
        $symbol = strtoupper($symbol);
        $cacheKey = $symbol;

        if (cache()->has($cacheKey)) {
            $companyName = cache()->get($cacheKey);
        } else {
            $companyName = Http::get(
                'https://finnhub.io/api/v1/stock/profile2?symbol=' . $symbol . '&token=' . $this->apiKey);
            cache()->put($cacheKey, $companyName->json(), now()->addDay());
        }
        $company = new Company(

            $companyName['name'],
            $symbol,
            $companyName['logo']
        );


        return $company;
    }

    public function getQuote(Company $company): Quote
    {
        $ResponseData = Http::get('https://finnhub.io/api/v1/quote?symbol=' . $company
                ->getSymbol() . '&token=' . $this->apiKey)->json();

        return new Quote(
            $ResponseData['o'],
            $ResponseData['pc'],
            $ResponseData['c']
        );

    }
}
