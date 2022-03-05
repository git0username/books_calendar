<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Laravel8から使うコントローラのuseが必要
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookOnloanController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\LoginController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 認証済みでないと許可しない
Route::group(["middleware" => ["auth:sanctum",'cache.headers:private;no_store;etag']], function () {
// Route::group(["middleware" => ["auth:sanctum"]], function () {


    Route::apiResource('/books',BookController::class);

    Route::apiResource('/bookonloan',BookOnloanController::class);

    Route::apiResource('/calendar',CalendarController::class)->except(['show']);    

    Route::post('/calendar/{booktypeId}', [CalendarController::class, 'NumberPerDay']);

    Route::get('/calendar/{booktypeId}/{studentNo}', [CalendarController::class, 'show']);

    // Route::get('/logout',  [LoginController::class, 'logout']);

    // Route::apiResource('/calendar/{booktypeId}/{studentNo}',[CalendarController::class, 'show1']);

    Route::get('/index', function () { return view('welcome');}); //これはweb.phpに記載されてるのでいらない？
    
});
    

// Laravel8から書き方が変更された
// apiに対応したrestfulにしておく

// Route::post('/login',[LoginController::class, 'authenticate']);
