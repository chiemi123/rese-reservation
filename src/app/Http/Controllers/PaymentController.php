<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function checkout(Request $request, $reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        // Stripeのシークレットキーを設定
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // 仮に金額を1人5000円で計算する（例）
        $amount = $reservation->number_of_people * 5000;

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'unit_amount' => $amount,
                    'product_data' => [
                        'name' => $reservation->shop->name . ' 食事代',
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('user.mypage', ['status' => 'success']),
            'cancel_url' => route('user.mypage', ['status' => 'cancel']),
            'metadata' => [
                'reservation_id' => $reservation->id, // Stripe側で確認可能！
                'user_id' => auth()->id(),
            ]
        ]);

        return redirect($session->url);
    }

    public function success($reservationId)
    {
        // 決済完了処理（例：支払済みフラグを更新）
        $reservation = Reservation::findOrFail($reservationId);

        // 支払い情報を保存（リレーション経由）
        $reservation->payment()->create([
            'stripe_payment_id' => 'session_' . uniqid(), // 本番では本物のIDを使う
            'amount' => $reservation->number_of_people * 5000,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->route('user.mypage')->with('payment_status', 'success');
    }

    public function cancel($reservationId)
    {
        return redirect()->route('user.mypage')->with('payment_status', 'cancel');
    }
}
