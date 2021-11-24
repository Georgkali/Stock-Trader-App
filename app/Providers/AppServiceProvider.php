<?php

namespace App\Providers;

use App\Repositories\Stock\FinnhubStocksRepository;
use App\Repositories\Stock\StocksRepository;
use App\Services\PurchaseService;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(StocksRepository::class, function () {
            return new FinnhubStocksRepository(env('FINNHUB_API_KEY'));
        });

    }

    public function boot()
    {
        //
    }
}
