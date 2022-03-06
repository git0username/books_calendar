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

//sanctum 認証ガードを指定
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 認証済みでないと許可しない
Route::group(["middleware" => ['cache.headers:private;no_store;etag']], function () {//"auth:sanctum"
// Route::group(["middleware" => ["auth:sanctum"]], function () {

    Route::apiResource('/books',BookController::class);

    Route::apiResource('/bookonloan',BookOnloanController::class);

    Route::apiResource('/calendar',CalendarController::class)->except(['show']);    

    Route::post('/calendar/{booktypeId}', [CalendarController::class, 'NumberPerDay']);

    Route::get('/calendar/{booktypeId}/{studentNo}', [CalendarController::class, 'show']);

    Route::get('/index', function () { return view('welcome');}); //これはweb.phpに記載されてるのでいらない？   
    
});
    

// Laravel8から書き方が変更された
// apiに対応したrestfulにしておく

// Route::group(['middleware' => ['web', ]], function () { //"auth:sanctum"
//     Route::post('/login',[LoginController::class, 'authenticate']);
// });
