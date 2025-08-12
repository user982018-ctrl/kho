<?php

use App\Http\Controllers\SaleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LadipageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FbWebHookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/ladipage', [LadipageController::class, 'index'])->name('ladipage');
Route::get('/checkSpam/{phone}', [LadipageController::class, 'checkSpam'])->name('checkSpam');
Route::post('/saveSpam', [LadipageController::class, 'saveSpam'])->name('saveSpam');

Route::get('/seach-sale-care',  [SaleController::class, 'seachSaleCareAPi'])->name('seach-sale-care-api');
Route::get('/variants-by-id-product',  [ProductController::class, 'getVariantsProductById'])->name('variant-by-id');

Route::get('/webhook-fb', [FbWebHookController::class, 'webhook'])->name('webhook-fb');