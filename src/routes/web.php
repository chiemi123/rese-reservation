<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\FavoriteController;

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

// Fortifyのregisterルートを無効化するために上書きする
Route::get('/register', [RegisterController::class, 'show'])->name('register');

Route::post('/register', [RegisterController::class, 'register']);

Route::get('/', [ShopController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('shops.index');

Route::get('/shops/{id}', [ShopController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('shops.show');

Route::post('/favorite/{shop}', [FavoriteController::class, 'store'])->name('favorite');
Route::delete('/favorite/{shop}', [FavoriteController::class, 'destroy'])->name('unfavorite');

Route::get('/thanks', function () {
    return view('thanks.register');
});

Route::get('/done', function () {
    return view('thanks.reservation');
});

// メール認証待ちページ
Route::get('/email/verify', function () {
    return view('auth.verify-email'); // メール送信しました的な画面
})->middleware('auth')->name('verification.notice');

// メール内リンクからのアクセス（認証完了処理）
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // 認証完了処理
    return redirect('/thanks'); // 認証完了後にサンクスページ
})->middleware(['auth', 'signed'])->name('verification.verify');

// 認証メールの再送信ルート
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送信しました！');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
