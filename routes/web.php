<?php

use App\Http\Controllers\SSOController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();
Route::get("/", function () {
    return view("welcome");
});

Route::get("/login", [SSOController::class, 'LoginSSO']);

Route::get("/callback", [SSOController::class, 'CallbackSSO']);
Route::get('/users', [SSOController::class, 'GetUserData']);
Route::get('/refresh/token', [SSOController::class, 'RefreshToken']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
