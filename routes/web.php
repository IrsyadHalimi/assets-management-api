<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('assets.index');
});

Route::get('/assets/print-pdf', [AssetController::class, 'generatePdf'])->name('assets.printPdf');
Route::post('/store-chart', [AssetController::class, 'storeChart']);