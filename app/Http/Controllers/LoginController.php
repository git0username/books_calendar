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
            $studentInfo = User::where('studentNo', $request->studentNo)->get(['studentNo','name'])->toArray();  //セキュリティ上全部のデータを持たせたくないのでいる分だけ精査          

            // dd( $studentInfo);
            
            return response(["result" => "success" , "studentInfo" => $studentInfo] ,200); //userinfo を全部持たせる
            // return $studentInfo;
        }

        return back()->withErrors([
            'userId' => 'The provided credentials do not match our records.',
        ]);
    }
}
