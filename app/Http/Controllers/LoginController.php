<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'studentNo' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            //adminかどうかcheck
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
        $request->session()->invalidate();
        $request->session()->regenerateToken();  // csrfトークンの再生成                
        return redirect('/login')->withHeaders([
            'Cache-Control' => 'no-store',
        ]);
    }

}
