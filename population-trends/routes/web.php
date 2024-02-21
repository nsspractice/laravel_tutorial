<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PopulationController;
use App\Http\Controllers\AreaController;
use App\Models\BasePopulation;

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

//areaとbase_populatoinの結合データを持ってくる
Route::get('/areaPop', [AreaController::class,'areaPop']);
Route::get('/yearData',[AreaController::class,'yearData']);

Route::get('/year',[PopulationController::class,'year']);
Route::get('/5sai',[PopulationController::class,'get5SAI']);
Route::get('/3sedai',[PopulationController::class,'get3SEDAI']);



