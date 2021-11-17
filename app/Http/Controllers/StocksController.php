<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class StocksController extends Controller
{
    public function search(Request $request)
    {
        $companyName = strtolower($request->get('query'));
        $cacheKey = Str::snake($companyName);
        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        $companies = Http::get(
            'https://finnhub.io/api/v1/search?q=' . $companyName . '&token=' . env('FINNHUB_API_KEY'));

        cache()->put($cacheKey, $companies->json('result'), now()->addMinute());

        return $companies->json('result');

    }
}
