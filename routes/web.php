<?php

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

Route::view('/', 'welcome');

Route::group(['prefix'=> '/trial-balances'], function () {
    Route::view('/','trial-balance');
    Route::view('add','add-trial-balance');
    Route::view('/{tb_id}', 'preview-trial-balance');
});

Route::group(['prefix'=> '/financial-reports'], function () {
    Route::view('/','financial-report');
    Route::view('add','add-financial-report');
    Route::view('/{report_id}', 'preview-financial-report');
});