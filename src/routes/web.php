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
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OwnerController;
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\Owner\Auth\LoginController as OwnerLoginController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\ShopController as OwnerShopController;
use App\Http\Controllers\Owner\ReservationController as OwnerReservationController;




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
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth:admin', 'role.admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/owners', [OwnerController::class, 'index'])->name('admin.owners.index');
    Route::get('/owners/create', [OwnerController::class, 'create'])->name('admin.owners.create');
    Route::post('/owners', [OwnerController::class, 'store'])->name('admin.owners.store');
    Route::delete('/owners/{owner}', [OwnerController::class, 'destroy'])->name('admin.owners.destroy');
});

// 店舗代表者のログイン処理
Route::prefix('owner')->name('owner.')->group(function () {
    // ログイン関連（未認証ユーザー用）
    Route::middleware('guest')->group(function () {
        Route::get('login', [OwnerLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [OwnerLoginController::class, 'login']);
    });



    // 店舗代表者用ページ（認証後）
    Route::middleware(['auth:owner', 'role.owner'])->group(function () {
        // ログアウト（認証済み）
        Route::post('logout', [OwnerLoginController::class, 'logout'])->name('logout');
        // ダッシュボード
        Route::get('dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

        // 店舗登録・編集など（全て /owner/shops/...）
        Route::get('shops/create', [OwnerShopController::class, 'create'])->name('shops.create');
        Route::post('shops', [OwnerShopController::class, 'store'])->name('shops.store');
        Route::get('shops/{shop}/edit', [OwnerShopController::class, 'edit'])->name('shops.edit');
        Route::put('shops/{shop}', [OwnerShopController::class, 'update'])->name('shops.update');

        // 予約一覧など
        Route::get('reservations', [OwnerReservationController::class, 'index'])->name('reservations.index');
    });
});
