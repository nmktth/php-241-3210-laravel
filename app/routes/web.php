<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;

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

//Comments
Route::controller(CommentController::class)->prefix('comment')->group(function(){
    Route::get('/', 'index')->name('comment.index');
    Route::post('/', 'store');
    Route::get('/edit/{comment}', 'edit');
    Route::post('/update/{comment}', 'update');
    Route::get('/delete/{comment}', 'delete');
    Route::get('/accept/{comment}', 'accept');
    Route::get('/reject/{comment}', 'reject');
});

//Article
Route::resource('/article', ArticleController::class)->middleware('auth:sanctum');

//Auth
Route::get('/auth/signin', [AuthController::class, 'signin']);
Route::post('/auth/registr', [AuthController::class, 'registr']);
Route::get('/auth/login', [AuthController::class, 'login'])->name('login');
Route::post('/auth/authenticate', [AuthController::class, 'authenticate']);
Route::get('/auth/logout', [AuthController::class, 'logout']);

//Main
Route::get('/', [MainController::class, 'index']);
Route::get('/full_image/{img}', [MainController::class, 'show']);

Route::get('/about', function(){
    return view('main.about');
});

Route::get('/contact', function(){
    $array = [
        'name'=>'Moscow Polytech',
        'adres' => 'B. Semenovskaya h.38',
        'email' => '..@maspolytech.ru',
        'phone' => '8(499)232-2222'
    ];
    return view('main.contact', ['contact'=>$array]);
});