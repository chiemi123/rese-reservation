<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::guard('admin')->user();

            // ロールが「admin」かチェック
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            } else {
                Auth::guard('admin')->logout(); // ログアウト処理（安全）
                return redirect()->route('admin.login')->withErrors([
                    'email' => '管理者アカウントではありません。',
                ]);
            }
        }

        // 認証失敗時のエラー
        return back()->withErrors([
            'email' => '認証情報が正しくありません。',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
