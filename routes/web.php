<?php

use App\Http\Controllers\AuthorizeController;
use Illuminate\Support\Facades\Route;

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

Route::view('/', 'home')->middleware('auth')->name('home');
Route::get('/authorize', [AuthorizeController::class, '__invoke'])->name('authorize');

Route::prefix('/trial-balances')->middleware('auth')->group(function () {
    Route::view('/','trial-balance');
    Route::view('add','add-trial-balance');
    Route::view('/{tb_id}', 'preview-trial-balance');
});


Route::prefix('/financial-statements')->middleware('auth')->group(function () {
    Route::view('/','financial-statement-collections'); 
    Route::view('add','add-financial-statement-collection'); 
    Route::view('/{collection_id}', 'preview-financial-statement-collection'); 
});

require __DIR__.'/auth.php';
