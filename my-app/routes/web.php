<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\CommentsController;

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

Route::resource('bbc',PostsController::class)->only(['index','store']);

Route::get('bbc/{id}',[PostsController::class,'show']);
Route::get('bbc/delete/{id}',[PostsController::class,'destroy']);

Route::get('create',function(){
    return view('bbc.create');
});

Route::resource('comment',CommentsController::class);


// Route::get('/bbc',[PostsController::class,'index']);
// Route::post('/post',[PostsController::class, 'store'])->name('bbc.store');
// Route::post('/comment',[CommentsController::class, 'store'])->name('comment.store');