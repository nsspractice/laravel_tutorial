<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\Ajax\SalesController;
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

//ブラウザにアクセス
//Route::get('sales', 'SaleController@index');
Route::get('sales',[SaleController::class,'index']);

//売上データ取得
Route::get('ajax/sales',[SalesController::class,'index']);
// Route::get('ajax/sales','Ajax\SalesController@index');

//年データ取得（セレクトボックス用）
Route::get('ajax/sales/years',[SalesController::class,'years']);
// Route::get('ajax/sales/years','Ajax\SalesController@years');