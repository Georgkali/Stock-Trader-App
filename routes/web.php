<?php

use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StocksController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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

require __DIR__.'/auth.php';
