<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    public function authenticate(Request $request)
    {    
        //adminかどうかcheck
        if(array_key_exists('name', $request->toArray()) && !($request->name == 'admin')){//nameが'admin'と一致しない場合
            return response(["result" => "admin_fail"] ,200); //認証はじく
        }

        //adminじゃない場合の処理↓
        $credentials = $request->validate([
            'studentNo' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) { //DBと認証取れたら
            $request->session()->regenerate();            
            $studentInfo_pre = User::where('studentNo', $request->studentNo)->get(['studentNo','name'])->toArray();  //セキュリティ上全部のデータを持たせたくないのでいる分だけ精査
            $studentInfo =array_reduce($studentInfo_pre, 'array_merge', array());
            
            return response(["result" => "success" , "studentInfo" => $studentInfo] ,200); //userinfo を全部持たせる
        }       

        return back()->withErrors([
            'studentNo' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate(); //ユーザーsessionを無効にする=logout
        $request->session()->regenerateToken();  // csrfトークンの再生成                
        return redirect('/login')->withHeaders([
            'Cache-Control' => 'no-store',
        ]);
    }

    public function userRegister(Request $request)
    {
        if(User::where('studentNo',$request->studentNo)->exists()){ //studentNoがすでに存在したらはじき返す
            return response(["result" => "exist"] ,200);   
        }else{
            $userInfo = $request->toArray();
            $userInfo['password'] =Hash::make($request->password);
            $User = new User;
            $User->fill($userInfo)->save();
            return response(["result" => "success"] ,200);
        }
    }

}
