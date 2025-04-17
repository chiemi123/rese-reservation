<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\AdminReservationController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Auth\UserLoginController;


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

Route::middleware('guest')->group(function () {
    Route::get('/login', [UserLoginController::class, 'create'])->name('login');
    Route::post('/login', [UserLoginController::class, 'login']);
});

Route::post('/logout', [UserLoginController::class, 'logout'])->name('logout');


Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

Route::get('/', [ShopController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('shops.index');

Route::get('/shops/{id}', [ShopController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('shops.show');

Route::post('/favorite/{shop}', [FavoriteController::class, 'store'])->name('favorite');
Route::delete('/favorite/{shop}', [FavoriteController::class, 'destroy'])->name('unfavorite');

Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');

Route::get('/thanks', function () {
    return view('thanks.register');
});

Route::get('/done', function () {
    return view('thanks.reservation');
})->name('reservation.thanks');

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

Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

Route::get('/reservations/confirm/{reservation}', [ReservationController::class, 'confirm'])
    ->name('reservation.confirm')
    ->middleware('signed');

Route::middleware(['auth'])->group(function () {
    // 予約
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::delete('/reservations/{id}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::put('/reservations/{id}/update', [ReservationController::class, 'update'])->name('reservations.update');
    Route::get('/reservations/{id}/qr', [ReservationController::class, 'showQr'])->name('reservations.qr');

    // 支払い
    Route::get('/checkout/{reservation}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/success/{reservation}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{reservation}', [PaymentController::class, 'cancel'])->name('payment.cancel');

    // マイページ
    Route::get('/mypage', [MyPageController::class, 'index'])->name('user.mypage');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return '管理者ダッシュボード';
    })->name('admin.dashboard');
});

Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin/reservations/unpaid', [AdminReservationController::class, 'unpaid'])->name('admin.reservations.unpaid');
});
