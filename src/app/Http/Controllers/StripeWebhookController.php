<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Reservation;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $secret);
        } catch (\Exception $e) {
            Log::error('Webhook検証失敗: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        Log::info('Webhook受信: ' . $event->type);

        // 決済成功イベント
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $reservationId = $session->metadata->reservation_id ?? null;
            Log::info('取得したreservation_id: ' . $reservationId);

            if ($reservationId) {
                $reservation = Reservation::find($reservationId);

                if (!$reservation) {
                    Log::warning("予約が見つかりません: ID = " . $reservationId);
                }

                if ($reservation && !$reservation->payment) {
                    $reservation->payment()->create([
                        'stripe_payment_id' => $session->id,
                        'amount' => $session->amount_total,
                        'status' => 'paid',
                        'paid_at' => now(),
                    ]);
                    Log::info("支払い情報を保存しました: reservation_id = {$reservationId}, stripe_id = {$session->id}");
                }

                if ($reservation && $reservation->payment) {
                    Log::info("既に支払い済みの予約です: ID = " . $reservationId);
                }
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}
