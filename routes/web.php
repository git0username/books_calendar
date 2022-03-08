<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BookController;


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


Route::middleware('cache.headers:private;no_store;etag')->group(function () {
    Route::get('/login',function () {
            return view('welcome'); //welcome.blade.php を返す
        })->name('login');//routeに名前を付ける middleware Authenticate.php 内のreturn先が route('login')になっているので、ログインページのパスに対してloginという名前を付けてあげると、認証漏れした時にログインページに飛ばされるようになる
        
    Route::post('/login',[LoginController::class, 'authenticate']);
    
    Route::post('/userRegister',[LoginController::class, 'userRegister']);
});

Route::get('/logout',  [LoginController::class, 'logout']);  


//認証済みでないと許可しない
Route::group(["middleware" => ["auth:sanctum",'cache.headers:private;no_store;etag']], function () {

    Route::get('/{any}', function () {
        return view('welcome'); //welcome.blade.php を返す
    })->where('any','.*'); //whereメソッドでパラメータを指定する where('パラメータ名', '正規表現')
        // })->where('any','^(?!login).*$'); //loginページ以外から入れないようにする けど 上から読込むので

        // Route::get('/logout',  [LoginController::class, 'logout']);  //middlewareに入れるとルーティングされない　なぜ
});







