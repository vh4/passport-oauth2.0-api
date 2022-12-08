<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use Laravel\Passport\Http\Controllers\AccessTokenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//middleware routes. scopes has been working to giving allow user only access by users only, or product only (like make limits access to get performed)
Route::group(['middleware' => ['auth:api', 'scope:view-user']], function(){ //Basic Middleware -> Route::group(['middleware' => 'auth:api'], function(){});
    Route::resource('user', UserController::class);
    Route::get('user/logout', [LoginController::class, 'logout']);
});

//access token without redresh token grant
// Route::post('login', [LoginController::class, 'login']);

// or access token

Route::post('login', [AccessTokenController::class, 'issueToken'])->middleware(['login_auth_api', 'throttle']);

//redirect to login if unauthorized!
Route::get('login', [LoginController::class, 'index'])->name('login');
;

//url not found
Route::any('{segment}', function () {
    return response()->json([
        'error' => 'Invalid url.'
    ]);
})->where('segment', '.*');

