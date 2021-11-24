<?php

use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StocksController;
use App\Http\Controllers\TradeHistoryRecordController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/portfolio', function () {
    return view('portfolio');
})->middleware(['auth'])->name('portfolio');

Route::get('/dashboard/search', [StocksController::class, 'search'])->name('stocks.search');
Route::get('/view', [StocksController::class, 'view'])->name('view');
Route::resource('purchase', PurchaseController::class);
Route::resource('wallet', WalletController::class);
Route::resource('history', TradeHistoryRecordController::class);
Route::get('openWallet', [WalletController::class, 'openWallet'])->name('wallet.openWallet');

require __DIR__.'/auth.php';
