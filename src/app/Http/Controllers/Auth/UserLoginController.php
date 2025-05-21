<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function login(UserLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('web')->user();

            // ✅ 管理者ユーザーはブロック（一般用画面ではログイン不可）
            if ($user->hasRole('admin') || $user->hasRole('shop_owner')) {
                Auth::guard('web')->logout();

                return back()->withErrors([
                    'email' => 'このログイン画面からはログインできません。',
                ])->withInput();
            }

            // 通常ユーザーとしてログイン成功
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        // 認証失敗
        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが間違っています。',
        ])->withInput();
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/login');
    }
}
