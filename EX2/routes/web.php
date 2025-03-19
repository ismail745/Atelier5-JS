<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/upload', [FileController::class, 'upload'])->name('files.upload');
Route::get('/files', [FileController::class, 'getFiles'])->name('files.list');