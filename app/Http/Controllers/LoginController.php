<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return response("success",200); //userinfo を全部持たせる
        }

        return back()->withErrors([
            'userId' => 'The provided credentials do not match our records.',
        ]);
    }
}
