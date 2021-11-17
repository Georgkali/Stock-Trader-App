<?php

use App\Http\Controllers\NotificationController;
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

Route::get('/email', [NotificationController::class, 'index'])->name('email');
Route::post('/email', [NotificationController::class, 'notification'])->name('email.notification');

Route::get('/stocks/search', [\App\Http\Controllers\StocksController::class, 'search']);
