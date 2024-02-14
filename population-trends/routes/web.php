<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PopulationController;
use App\Http\Controllers\AreaController;
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

//メイン画面の表示
Route::get('/',function(){
    return view('leaflet');
});

//base_populationのデータを全部持ってくる
Route::get('/pop', [PopulationController::class,'basePopulation']);

//areaのデータを全部持ってくる
Route::get('/area', [AreaController::class,'area']);
