<?php

namespace App\Http\Controllers\Owner\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\OwnerLoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('owner.auth.login');
    }

    public function login(OwnerLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->hasRole('shop_owner')) {
                return redirect()->intended('/owner/dashboard');
            } else {
                Auth::logout();
                return redirect()->route('owner.login')->withErrors(['email' => '店舗代表者アカウントではありません']);
            }
        }

        return back()->withErrors(['email' => 'メールアドレスまたはパスワードが正しくありません']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('owner.login');
    }
}
