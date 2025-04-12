<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function showPaymentForm($id)
    {
        $reservation = Reservation::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('user.payment-form', compact('reservation'));
    }

    public function processPayment(Request $request, $id)
    {
        $reservation = Reservation::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => '飲食店予約：' . $reservation->shop->name,
                    ],
                    'unit_amount' => 3000, // 価格（例：3000円）
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('user.mypage'),
            'cancel_url' => route('payment.form', ['id' => $reservation->id]),
        ]);

        return redirect($session->url);
    }
}
