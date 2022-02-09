<?php

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


Route::get('/login',function () {
        return view('welcome'); //welcome.blade.php を返す
    })->name('login');

//認証済みでないと許可しない
Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::get('/{any}', function () {
        return view('welcome'); //welcome.blade.php を返す
    })->where('any','.*'); //whereメソッドでパラメータを指定する where('パラメータ名', '正規表現')
        // })->where('any','^(?!login).*$'); //loginページ以外から入れないようにする けど 上から読込むので
});

// Route::get('/login',function () {
//     return view('welcome'); //welcome.blade.php を返す
// })->name('login'); //routeに名前を付ける middleware Authenticate.php 内のreturn先が route('login')になっているので、ログインページのパスに対してloginという名前を付けてあげると、認証漏れした時にログインページに飛ばされるようになる


