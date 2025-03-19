<?php

use Illuminate\Support\Facades\Route;
// routes/web.php
use App\Http\Controllers\StockController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
Route::post('/stocks', [StockController::class, 'store'])->name('stocks.store');
Route::put('/stocks/{id}', [StockController::class, 'update'])->name('stocks.update');
Route::delete('/stocks/{id}', [StockController::class, 'destroy'])->name('stocks.destroy');